<?php

namespace App\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Categorias')]
class CategoryComponent extends Component
{
    use WithPagination;

    // Propiedades clase
    public $categoryCount = 0, $search = '';
    public $pagination = 5;

    // Propiedades modelo
    public $name;
    public $Id;

    public function mount()
    {
        $this->categoryCount();
    }

    public function categoryCount()
    {
        $this->categoryCount = Category::count();
    }

    // Crear categoria
    public function store()
    {

        $this->validate([
            'name' => 'required|min:5|max:255|unique:categories'
        ]);

        Category::create([
            'name' => $this->name
        ]);

        $this->categoryCount();
        $this->dispatch('close-modal', 'modalCategory');
        $this->dispatch('msg', 'Categoria creada correctamente!');
        $this->reset(['name']);
    }

    public function create(){

        $this->Id = 0;
        $this->reset(['name']);
        $this->resetErrorBag();
        $this->dispatch('open-modal', 'modalCategory');
    }

    public function edit(Category $category){

        $this->reset(['name']);
        $this->Id = $category->id;
        $this->name = $category->name;
        $this->dispatch('open-modal', 'modalCategory');
    }

    public function update(Category $category){

        $this->validate([
            'name' => 'required|min:5|max:255|unique:categories,id,'.$this->Id
        ]);

        $category->name = $this->name;
        $category->update();

        $this->dispatch('close-modal', 'modalCategory');
        $this->dispatch('msg', 'Categoria actualizada correctamente!');
        $this->reset(['name']);
    }

    #[On('destroyCategory')]
    public function destroy($id){

        $category = Category::findOrFail($id);
        $category->delete();

        $this->categoryCount();
        $this->dispatch('msg', 'Categoria ha sido eliminada correctamente!');
    }

    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }
        $categories = Category::where('name', 'LIKE', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->pagination);

        return view('livewire.category.category-component', compact('categories'));
    }
}
