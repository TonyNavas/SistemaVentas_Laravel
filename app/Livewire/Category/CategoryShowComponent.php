<?php

namespace App\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Gate;

#[Title('Ver categoria')]
class CategoryShowComponent extends Component
{
    use WithPagination;

    public Category $category;

    public function render()
    {
        Gate::authorize('ver-categorias');
        $products = $this->category->products()->paginate(5);
        return view('livewire.category.category-show-component', compact('products'));
    }
}
