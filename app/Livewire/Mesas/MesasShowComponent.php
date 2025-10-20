<?php

namespace App\Livewire\Mesas;

use Log;
use App\Models\Item;
use App\Models\Sale;
use App\Models\User;
use App\Models\Order;
use App\Models\Table;
use App\Models\Product;
use Livewire\Component;
use App\Events\CreateOrder;
use App\Models\OrderDetail;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\NumerosEnLetras;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Notification;

#[Title('Detalle de cabaña')]
class MesasShowComponent extends Component
{
    use WithPagination;

    public Table $table;
    public $mesaToken;
    public $client_name = '';

    // Propiedades clase
    public $search = '';
    public $pagination = 7;
    public $tablesProductsCount = 0;

    // Propiedades para el pago
    public $pago = 0;
    public $cambio = 0;
    public $updating = 0;

    public $selectedOrderDetails = [];
    public $showDetailsModal = false;

    public function mount()
    {
        $this->client_name = $this->table->client_name ?? '';
        $this->mesaToken = $this->table->token;
    }

    public function updatedClientName($value)
    {
        $this->table->client_name = $value;
        $this->table->save();
    }

    public function getTotalArticles()
    {
        return Cart::instance($this->table->code)->count();
    }

    // Funcionalidades del carrito temporal
    #[On('add-product')]
    public function addProduct(Product $product)
    {
        $this->updating = 0;

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
        $this->updating = 0;
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
        $this->updating = 0;
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
        $this->updating = 0;
        // Obtener el producto del carrito
        $item = Cart::instance($this->table->code)->search(function ($item, $rowId) use ($id) {
            return $item->id === $id;
        })->first();

        if ($item) {
            Cart::instance($this->table->code)->remove($item->rowId);
        }

        $this->dispatch("devolverStock.{$id}", $qty);
    }

    #[On('setPago')]
    public function setPago($valor)
    {
        $this->updating = 1;
        $this->pago = $valor;
        $this->cambio = $this->pago - $this->ordersTotal();
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
            $order->user_id = Auth::user()->id;
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
            $this->reset(['pago', 'cambio']);
            $this->dispatch('msg', 'Pedido realizado correctamente', 'success');

            CreateOrder::dispatch($order, $this->table->token);

            $users = User::role(['mesero', 'cocinero', 'administrador'])
                ->where('id', '!=', Auth::user()->id)
                ->get();

            $url = route('kitchen.index');

            Notification::send($users, new NewOrderNotification($order, $url));
        });
    }

    // Crear venta

    public function createSale()
    {
        // Traer todas las órdenes "abiertas" de la mesa
        $orders = Order::where('table_id', $this->table->id)
            ->whereNull('sale_id') // Aún no facturadas
            ->get();

        if ($orders->isEmpty()) {
            $this->dispatch('msg', 'No hay órdenes para facturar en esta mesa', 'danger');
            return;
        }

        $total = $orders->sum('total');

        DB::transaction(function () use ($orders, $total) {
            $sale = new Sale();
            $sale->total = $total;
            $sale->pago = $this->pago; // se llena al momento de pagar
            $sale->cambio = $this->pago - $total;
            $sale->fecha = date('Y-m-d');
            $sale->user_id = Auth::user()->id;
            $sale->table_id = $this->table->id;
            $sale->save();

            // Vincular todas las órdenes a esta venta
            foreach ($orders as $order) {
                $order->sale_id = $sale->id;
                $order->status = 'pagado'; // o 'cerrada'
                $order->save();
            }

            $this->reset(['pago', 'cambio']);
            $this->dispatch('msg', 'Venta registrada correctamente', 'success', $sale->id);

            // Cerrar la mesa
            $this->closeTable($this->table);
            redirect()->route('sales.list');
        });
    }



    public function limpiarMesa(Table $table)
    {
        Cart::instance($table->code)->destroy();
        $table->products()->detach();
    }

    public function closeTable(Table $table)
    {
        Gate::authorize('cerrar-mesa');

        // Si no hay venta asociada, no permitir cerrar
        if ($table->orders()->whereNull('sale_id')->exists()) {
            $this->dispatch('msg', 'No puedes cerrar la mesa sin registrar el pago.', 'danger');
            return;
        }

        // Vaciar el carrito
        Cart::instance($table->code)->destroy();

        // Limpiar y cerrar mesa
        $table->client_name = null;
        // Cambiar el estado de la mesa a "closed"
        $table->status = 'closed';
        $table->token = null;
        $table->save();

        return redirect()->route('tables.index');
    }


    #[Computed()]
    public function products()
    {
        return Product::where('name', 'LIKE', '%' . $this->search . '%')
            ->where('active', 1) // 👈 solo productos activos
            ->orderBy('id', 'desc')
            ->paginate($this->pagination);
    }


    public function updatingPago($value)
    {
        $this->updating = 1;
        $this->pago =  $value;
        $this->cambio =  (int)$this->pago - $this->ordersTotal();
    }

    #[Computed()]
    public function cart()
    {
        $cart = Cart::instance($this->table->code)->content();
        return $cart;
    }

    #[Computed()]
    public function orders()
    {
        return $this->table->orders()->whereIn('status', ['nuevo', 'en_proceso', 'listo', 'entregado'])->with('details.product')->get();
    }

    #[Computed()]
    public function cartTotal()
    {
        $cartTotal = Cart::instance($this->table->code)->total(2, '.', '');
        return $cartTotal;
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

    #[On('echo-private:orders.{mesaToken},ChangeOrderStatus')]
    public function refreshOrders()
    {
        $this->dispatch('$refresh'); // fuerza recalcular los #[Computed]
    }


    public function render()
    {
        Gate::authorize('ver-mesa');
        if ($this->search != '') {
            $this->resetPage();
        }

        if ($this->updating == 0) {
            $this->pago =  $this->ordersTotal();
            $this->cambio =  $this->pago - $this->ordersTotal();
        }

        return view('livewire.mesas.mesas-show-component', [
            'products' => $this->products,
            'cart' => $this->cart,
            'cartTotal' => $this->cartTotal(),
            'orders' => $this->orders(),
            'ordersTotal' => $this->ordersTotal(),
        ]);
    }
}
