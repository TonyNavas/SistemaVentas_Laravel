<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Storage;

#[Title('Productos')]
class ProductComponent extends Component
{

    use WithFileUploads;
    use WithPagination;

    // Propiedades clase
    public $productCount = 0,  $search = '';
    public $pagination = 5;

    // Propiedades modelo
    public $Id = 0;
    public $name;
    public $category_id;
    public $desc;
    public $precio_compra;
    public $precio_venta;
    public $codigo_barras;
    public $stock = 0;
    public $stock_minimo = 10;
    public $fecha_vencimiento;
    public $active;
    public $image;
    public $imageModel;

    public function mount()
    {
        $this->productCount();
    }

    public function productCount()
    {
        $this->productCount = Product::count();
    }

    public function create()
    {
        Gate::authorize('crear-productos');
        $this->Id = 0;
        $this->resetUI();
        $this->resetErrorBag();
        $this->dispatch('open-modal', 'modalProduct');
    }

    // Crear categoria
    public function store()
    {
        Gate::authorize('crear-productos');
        $this->validate([
            'name' => 'required|min:5|max:255|unique:products',
            'desc' => 'max:255',
            'precio_compra' => 'numeric|nullable',
            'precio_venta' => 'required|numeric',
            'stock' => 'required|numeric',
            'stock_minimo' => 'numeric|nullable',
            'image' => 'image|max:1024|nullable',
            'category_id' => 'required|numeric',
        ]);

        $product = new Product();

        $product->name = $this->name;
        $product->desc = $this->desc;
        $product->precio_compra = $this->precio_compra;
        $product->precio_venta = $this->precio_venta;
        $product->stock = $this->stock;
        $product->stock_minimo = $this->stock_minimo;
        $product->codigo_barras = $this->codigo_barras;
        $product->fecha_vencimiento = $this->fecha_vencimiento;
        $product->category_id = $this->category_id;
        $product->active = $this->active;
        $product->save();

        if ($this->image) {
            $customName = 'products/' . uniqid() . '.' . $this->image->extension();
            $this->image->storeAs('public', $customName);
            $product->image()->create(['url' => $customName]);
        }

        $this->productCount();
        $this->dispatch('close-modal', 'modalProduct');
        $this->dispatch('msg', 'Producto creado correctamente!');
        $this->resetUI();
    }

    public function edit(Product $product)
    {
        Gate::authorize('modificar-productos');
        $this->resetUI();

        $this->Id = $product->id;
        $this->name = $product->name;
        $this->desc = $product->desc;
        $this->precio_compra = $product->precio_compra;
        $this->precio_venta = $product->precio_venta;
        $this->stock = $product->stock;
        $this->stock_minimo = $product->stock_minimo;
        $this->imageModel = $product->imagen;
        $this->codigo_barras = $product->codigo_barras;
        $this->fecha_vencimiento = $product->fecha_vencimiento;
        $this->active = $product->active ? true : false;
        $this->category_id = $product->category_id;

        $this->dispatch('open-modal', 'modalProduct');
    }

    public function update(Product $product)
    {
        Gate::authorize('modificar-productos');
        $this->validate([
            'name' => 'required|min:5|max:255|unique:products,id,' . $this->Id,
            'desc' => 'max:255',
            'precio_compra' => 'numeric|nullable',
            'precio_venta' => 'required|numeric',
            'stock' => 'required|numeric',
            'stock_minimo' => 'numeric|nullable',
            'image' => 'image|max:1024|nullable',
            'category_id' => 'required|numeric',
        ]);

        $product->name = $this->name;
        $product->desc = $this->desc;
        $product->precio_compra = $this->precio_compra;
        $product->precio_venta = $this->precio_venta;
        $product->stock = $this->stock;
        $product->stock_minimo = $this->stock_minimo;
        $product->codigo_barras = $this->codigo_barras;
        $product->fecha_vencimiento = $this->fecha_vencimiento;
        $product->category_id = $this->category_id;
        $product->active = $this->active;
        $product->update();

        if ($this->image) {
            if ($product->image != null) {
                Storage::delete('public/' . $product->image->url);
                $product->image()->delete();
            }
            $customName = 'products/' . uniqid() . '.' . $this->image->extension();
            $this->image->storeAs('public', $customName);
            $product->image()->create(['url' => $customName]);
        }

        $this->dispatch('close-modal', 'modalProduct');
        $this->dispatch('msg', 'Producto actualizado correctamente!');
        $this->resetUI();
    }

    #[On('destroyProduct')]
    public function destroy($id)
    {
        Gate::authorize('eliminar-productos');
        $product = Product::findOrFail($id);
        if ($product->image != null) {
            Storage::delete('public/' . $product->image->url);
            $product->image()->delete();
        }

        $product->delete();

        $this->productCount();
        $this->dispatch('msg', 'Producto eliminado correctamente!');
    }

    // Limpieza de todos los campos
    public function resetUI()
    {
        $this->reset(['Id', 'name', 'image', 'desc', 'precio_compra', 'precio_venta', 'stock', 'stock_minimo', 'codigo_barras', 'fecha_vencimiento', 'active', 'category_id']);
        $this->resetErrorBag();
    }

    #[Computed()]
    public function categories()
    {
        return Category::all();
    }

    public function render()
    {
        Gate::authorize('ver-productos');
        $products = Product::where('name', 'LIKE', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->pagination);

        return view('livewire.product.product-component', compact('products'));
    }
}
