<?php

namespace App\Livewire\Mesas;

use Livewire\Component;

class Currency extends Component
{

    public $total = 0;
    public $valores = [];

    public function mount(){
        $this->valores = [1000,500,200,100,50,20,10,5,1];
    }

    public function openModal(){
        $this->dispatch('open-modal', 'modalCurrency');
    }

    public function render()
    {
        return view('livewire.mesas.currency');
    }
}
