<?php

namespace App\Livewire\Mesas;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class Currency extends Component
{
    #[Reactive]
    public $total = 0;
    public $valores = [];

    public function mount(){
        $this->valores = [5000,4000,3000,2000,1000,500,200,100,50,20,10,5,1];
    }

    public function setPago($valor){

        $this->dispatch('setPago', $valor);
        $this->dispatch('close-modal', 'modalCurrency');

    }

    public function openModal(){
        $this->dispatch('open-modal', 'modalCurrency');
    }

    public function render()
    {
        return view('livewire.mesas.currency');
    }
}
