<?php

namespace App\Livewire\Mesas;

use App\Models\Table;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\NumerosEnLetras;
use Livewire\Attributes\Computed;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Attributes\On;

#[Title('Detalle')]
class MesasShowComponent extends Component
{
    use WithPagination;

    public Table $table;

    // Propiedades clase
    public $search = '';
    public $pagination = 5;
    public $tablesProductsCount = 0;

    public $pago = 0;
    public $cambio = 0;
    public $updating = 0;

    public function mount()
    {
        $this->recuperarCarrito();
    }

    public function getTotalArticles()
    {
        return Cart::instance($this->table->code)->count();
    }

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

        $this->mantenerCarrito();
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
        // Obtener el producto del carrito
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

    public function numerosLetras($number)
    {
        return NumerosEnLetras::convertir($number, 'cordobas', false, 'centavos');
    }

    #[Computed()]
    public function products()
    {
        return Product::where('name', 'LIKE', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->pagination);
    }

    #[Computed()]
    public function cart()
    {
        $cart = Cart::instance($this->table->code)->content();
        return $cart;
    }

    public function updatingPago($value)
    {
        dump($value);
        $this->updating = 1;
        $this->pago =  $value;
        $this->cambio =  $this->pago - Cart::instance($this->table->code)->total(2,'.','');
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }

        $cartTotal = Cart::instance($this->table->code)->total(2, '.', '');

        if ($this->updating == 0) {
            $this->pago =  Cart::instance($this->table->code)->total(2, '.', '');
            $this->cambio =  $this->pago - Cart::instance($this->table->code)->total(2,'.','');
        }

        return view('livewire.mesas.mesas-show-component', [
            'products' => $this->products,
            'cart' => $this->cart,
            'cartTotal' => $cartTotal,
        ]);
    }
}
