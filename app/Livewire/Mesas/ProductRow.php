<?php

namespace App\Livewire\Mesas;

use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;

class ProductRow extends Component
{
    public Product $product;
    public $stock;
    public $stockLabel;

    protected function getListeners()
    {
        return [
            "decrementStock.{$this->product->id}" => "decrementStock",
            "incrementStock.{$this->product->id}" => "incrementStock",
            "refreshProducts" => "mount",
            "devolverStock.{$this->product->id}" => "devolverStock",
        ];
    }

    public function mount(){
        $this->stock = $this->product->stock;
    }

    public function stockLabel(){
        if($this->stock <= $this->product->stock_minimo){
            return '<span class="badge badge-pill badge-danger">'.$this->stock.'</span>';
        }else{
            return '<span class="badge badge-pill badge-primary">'.$this->stock.'</span>';
        }
    }
    //Agregar un producto al carrito
    public function addProduct(Product $product){

        if($this->stock==0){
            return;
        }
        $this->dispatch('add-product', $product);
        $this->stock--;
    }

    public function decrementStock(){
        $this->stock--;
    }

    public function incrementStock(){

        //if($this->stock == $this->product->stock-1){
        //    return;
        //}

        $this->stock++;
    }

    public function devolverStock($qty){
        $this->stock = $this->stock+$qty;
    }

    public function render()
    {
        $this->stockLabel = $this->stockLabel();

        return view('livewire.mesas.product-row');
    }
}
