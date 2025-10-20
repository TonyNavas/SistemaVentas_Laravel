<?php

namespace App\Livewire\Client;

use App\Models\User;
use App\Models\Order;
use App\Models\Table;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Events\CreateOrder;
use App\Models\OrderDetail;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Notification;

#[Title('Cliente')]
class MesaClientComponent extends Component
{
    use WithPagination;

    // Propiedad para inicializar la mesa
    public Table $table;
    public $client_name = '';
    public $mesaToken;


    public $category_id;
    public $search = '';
    public $pagination = 5;

    public function mount($token)
    {
        $this->table = Table::where('token', $token)->firstOrFail();
        $this->client_name = $this->table->client_name ?? '';
        $this->mesaToken = $token;

        // 🔥 Limpia el carrito al entrar a la mesa
        Cart::instance($this->table->code)->destroy();
    }


    public function updatedClientName($value)
    {
        $this->table->client_name = $value;
        $this->table->save();
    }


    #[On('addProduct')]
    public function addProduct(Product $product)
    {
        // Verificar si el producto ya está en el carrito
        $existingItem = Cart::instance($this->table->code)->search(function ($cartItem, $rowId) use ($product) {
            return $cartItem->id == $product->id;
        })->first(); // Obtener el primer elemento de la colección

        if ($existingItem) {
            // Si el producto ya está en el carrito, incrementar su cantidad
            Cart::instance($this->table->code)->update($existingItem->rowId, $existingItem->qty + 1);
        } else {
            // Si el producto no está en el carrito, agregarlo
            Cart::instance($this->table->code)->add([
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->precio_venta,
                'qty' => 1,
                'weight' => 1,
                'options' => [
                    'image' => $product,
                ]
            ]);
        }
    }

    public function increment($id)
    {
        // Obtener el producto del carrito
        $item = Cart::instance($this->table->code)->search(function ($item, $rowId) use ($id) {
            return $item->id === $id;
        })->first();

        // Verificar que el item existe
        if ($item) {
            // Incrementar la cantidad en el carrito
            Cart::instance($this->table->code)->update($item->rowId, $item->qty + 1);
        }

        $this->dispatch("decrementStock.{$id}");
    }

    public function decrement($id)
    {
        $item = Cart::instance($this->table->code)->search(function ($item, $rowId) use ($id) {
            return $item->id === $id;
        })->first();

        // Verificar que el item existe y su cantidad es mayor que 1
        if ($item && $item->qty > 1) {
            // Decrementar la cantidad en el carrito
            Cart::instance($this->table->code)->update($item->rowId, $item->qty - 1);
        } else {
            // Si la cantidad es 1 o menos, eliminar el producto del carrito y de la base de datos
            Cart::instance($this->table->code)->remove($item->rowId);
        }

        $this->dispatch("incrementStock.{$id}");
    }

    public function removeItem($id, $qty)
    {
        // Obtener el producto del carrito
        $item = Cart::instance($this->table->code)->search(function ($item, $rowId) use ($id) {
            return $item->id === $id;
        })->first();

        if ($item) {
            Cart::instance($this->table->code)->remove($item->rowId);
        }

        $this->dispatch("devolverStock.{$id}", $qty);
    }

    // Crear pedido

    public function createOrder()
    {

        if (empty($this->table->client_name)) {
            $this->dispatch('msg', 'Debe ingresar su nombre antes de realizar un pedido', 'warning');
            return;
        }

        $cart = Cart::instance($this->table->code)->content();

        if (count($cart) == 0) {
            $this->dispatch('msg', 'No hay productos en el carrito', 'danger');
            return;
        }

        DB::transaction(function () {
            $order = new Order();
            $order->status = 'nuevo';
            $order->total = Cart::instance($this->table->code)->total(2, '.', '');
            // $order->pago = null; //Quitarlo y luego rellenarlo al hacer la venta
            $order->fecha = date('Y-m-d');
            $order->notas = '';
            $order->user_id = null;
            $order->table_id = $this->table->id;
            $order->save();

            // Agregar el detalle de la orden
            foreach (Cart::instance($this->table->code)->content() as $product) {
                $detail = new OrderDetail();

                $detail->name = $product->name;
                $detail->image = $product->options->image->imagen;
                $detail->price = $product->price;
                $detail->quantity = $product->qty;
                $detail->fecha = date('Y-m-d');
                $detail->subtotal = $product->qty * $product->price;
                $detail->status = 'nuevo';
                $detail->order_id = $order->id;
                $detail->product_id = $product->id;
                $detail->save();

                Product::find($product->id)->decrement('stock', $product->qty);
            }

            // Eliminanos el contenido del carrito
            Cart::instance($this->table->code)->destroy();
            $this->dispatch('msg', 'Pedido realizado correctamente', 'success');

            CreateOrder::dispatch($order, $this->table->token);

            $users = User::role(['mesero', 'cocinero', 'administrador'])->get();


            $url = route('kitchen.index');

            Notification::send($users, new NewOrderNotification($order, $url));
        });
    }

    #[Computed()]
    public function cart()
    {
        $cart = Cart::instance($this->table->code)->content();
        return $cart;
    }

    #[Computed()]
    public function cartTotal()
    {
        $cartTotal = Cart::instance($this->table->code)->total(2, '.', '');
        return $cartTotal;
    }

    #[Computed()]
    public function orders()
    {
        return $this->table->orders()->whereIn('status', ['nuevo', 'en_proceso', 'listo', 'entregado'])->with('details.product')->get();
    }
    #[Computed()]
    public function ordersTotal()
    {
        // Suma todos los subtotales de todas las órdenes de la mesa
        return $this->table->orders()
            ->where('status', '!=', 'pagado') // solo órdenes pendientes
            ->with('details')
            ->get()
            ->flatMap->details
            ->sum('subtotal');
    }

    #[On('echo:orders.{mesaToken},ChangeOrderStatus')]
    public function refreshOrders()
    {
        $this->dispatch('$refresh'); // 🔄 fuerza recalcular los #[Computed]
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryId()
    {
        $this->resetPage();
    }


    public function render()
    {
        $query = Product::where('active', true);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->category_id) {
            $query->where('category_id', $this->category_id);
        }

        $products = $query->orderBy('id', 'desc')
            ->paginate($this->pagination)
            ->withQueryString(); // 👈 Mantiene los filtros al cambiar de página

        return view('livewire.client.mesa-client-component', [
            'products' => $products,
            'categories' => Category::all(),
            'cart' => $this->cart,
            'cartTotal' => $this->cartTotal(),
            'orders' => $this->orders,
            'ordersTotal' => $this->ordersTotal(),
        ])->extends('layouts.app')->section('content');
    }
}
