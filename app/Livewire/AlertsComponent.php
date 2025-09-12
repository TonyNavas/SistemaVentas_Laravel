<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class AlertsComponent extends Component
{
    #[On('msg')]
    public function msgs($msg, $type = "success", $sale = null, $order = null){
        session()->flash('msg', $msg);
        session()->flash('type', $type);
        session()->flash('sale', $sale);
        session()->flash('order', $order);
    }

    public function render()
    {
        return view('livewire.alerts-component');
    }
}
