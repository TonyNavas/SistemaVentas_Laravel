<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Gate;

#[Title('Ver producto')]
class ProductShowComponent extends Component
{
    public Product $product;

    public function render()
    {
        Gate::authorize('ver-productos');
        return view('livewire.product.product-show-component');
    }
}
