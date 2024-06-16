<?php

namespace App\Livewire\Mesas;

use App\Models\Table;
use App\Models\Product;
use Livewire\Component;
use App\Models\TableProduct;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\NumerosEnLetras;
use Livewire\Attributes\Computed;
use Gloudemans\Shoppingcart\Facades\Cart;

#[Title('Detalle')]
class MesasShowComponent extends Component
{
    use WithPagination;

    public Table $table;

    // Propiedades clase
    public $search = '';
    public $pagination = 5;
    public $tablesProductsCount = 0;

    public function mount()
    {
        $this->tablesProductsCount();
        $this->recuperarCarrito();
    }

    public function tablesProductsCount()
    {
        $this->tablesProductsCount = TableProduct::count();
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

        $this->mantenerCarrito();
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

    public function closeTable(Table $table)
    {
        // Eliminar los productos asociados a la mesa
        $table->products()->detach();

        // Cambiar el estado de la mesa a "close"
        $table->status = 'closed';
        $table->save();

        return redirect()->route('tables.index');
    }

    public function numerosLetras($number){
        return NumerosEnLetras::convertir($number, 'Cordobas',false,'Centavos');
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
    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }
        $cartTotal = (float) Cart::instance($this->table->code)->total(2, '.', '');
        return view('livewire.mesas.mesas-show-component', [
            'products' => $this->products,
            'cart' => $this->cart,
            'cartTotal' => $cartTotal,
        ]);
    }
}
