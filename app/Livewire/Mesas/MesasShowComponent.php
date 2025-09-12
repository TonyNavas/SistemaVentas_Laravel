<?php

namespace App\Livewire\Mesas;

use App\Models\Item;
use App\Models\Table;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\NumerosEnLetras;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Sale;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;

#[Title('Detalle de cabaña')]
class MesasShowComponent extends Component
{
    use WithPagination;

    public Table $table;

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
        $this->recuperarCarrito();
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

        // $this->mantenerCarrito();
    }

    public function mantenerCarrito()
    {
        // Eliminar productos anteriores asociados a la mesa
        $this->table->products()->detach();

        // Agregar los productos actuales del carrito asociados a la mesa
        $cartContent = Cart::instance($this->table->code)->content();

        foreach ($cartContent as $item) {
            $this->table->products()->attach($item->id, ['quantity' => $item->qty]);
        }
    }

    public function recuperarCarrito()
    {
        // Limpiar el carrito antes de recuperar los productos de la mesa
        Cart::instance($this->table->code)->destroy();

        // Recuperar los productos asociados a la mesa y agregarlos al carrito
        $mesaProducts = $this->table->products;

        foreach ($mesaProducts as $product) {
            Cart::instance($this->table->code)->add([
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->precio_venta,
                'qty' => $product->pivot->quantity,
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

            // Incrementar la cantidad en la base de datos
            $this->table->products()->updateExistingPivot($item->id, ['quantity' => $item->qty + 1]);
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

            // Decrementar la cantidad en la base de datos
            $this->table->products()->updateExistingPivot($item->id, ['quantity' => $item->qty - 1]);
        } else {
            // Si la cantidad es 1 o menos, eliminar el producto del carrito y de la base de datos
            Cart::instance($this->table->code)->remove($item->rowId);
            $this->table->products()->detach($item->id);
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
            $this->table->products()->detach($item->id);
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
        $cart = Cart::instance($this->table->code)->content();

        if (count($cart) == 0) {
            $this->dispatch('msg', 'No hay productos en el carrito', 'danger');
            return;
        }

        DB::transaction(function () {
            $order = new Order();
            $order->status = 'nuevo';
            $order->total = Cart::instance($this->table->code)->total(2, '.', '');
            $order->pago = null; //Quitarlo y luego rellenarlo al hacer la venta
            $order->fecha = date('Y-m-d');
            $order->notas = '';
            $order->user_id = Auth::user()->id;
            $order->table_id = $this->table->id;
            $order->save();

            // Agregar el detalle de la orden
            foreach (Cart::instance($this->table->code)->content() as $product) {
                $detail = new OrderDetail();

                $detail->quantity = $product->qty;
                $detail->unitary_price = $product->price;
                $detail->subtotal = $product->qty * $product->price;
                $detail->image = $product->options->image->imagen;
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

            // // Cerrar la mesa
            // $this->table->status = 'closed';
            // $this->table->save();

            $this->reset(['pago', 'cambio']);
            $this->dispatch('msg', 'Venta registrada correctamente', 'success', $sale->id);
        });
    }

    public function limpiarMesa(Table $table)
    {
        Cart::instance($table->code)->destroy();
        $table->products()->detach();
    }

    public function closeTable(Table $table)
    {
        // Eliminar todos los productos del carrito
        Cart::instance($table->code)->destroy();

        // Eliminar los productos asociados a la mesa en la base de datos
        $table->products()->detach();

        // Cambiar el estado de la mesa a "closed"
        $table->status = 'closed';
        $table->save();

        return redirect()->route('tables.index');
    }

    #[Computed()]
    public function products()
    {
        return Product::where('name', 'LIKE', '%' . $this->search . '%')
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
        // return $this->table->orders()->with('details.product')->get();
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


    public function render()
    {
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
