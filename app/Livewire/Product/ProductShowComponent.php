<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Ver producto')]
class ProductShowComponent extends Component
{
    public Product $product;

    public function render()
    {
        return view('livewire.product.product-show-component');
    }
}
