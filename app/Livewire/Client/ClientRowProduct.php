<?php

namespace App\Livewire\Client;

use App\Models\Product;
use Livewire\Component;

class ClientRowProduct extends Component
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
            return '<span class="badge  bg-danger">'.$this->stock.'</span>';
        }else{
            return '<span class="badge  bg-primary">'.$this->stock.'</span>';
        }
    }

    public function addProduct(Product $product){

        if($this->stock==0){
            return;
        }
        $this->dispatch('addProduct', $product);
        $this->stock--;
    }
        public function decrementStock(){
        $this->stock--;
    }

    public function incrementStock(){
        $this->stock++;
    }

    public function devolverStock($qty){
        $this->stock = $this->stock+$qty;
    }


    public function render()
    {
        $this->stockLabel = $this->stockLabel();
        return view('livewire.client.client-row-product');
    }
}
