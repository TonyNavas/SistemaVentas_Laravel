<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class AlertsComponent extends Component
{
    #[On('msg')]
    public function msgs($msg){
        session()->flash('msg', $msg);
    }

    public function render()
    {
        return view('livewire.alerts-component');
    }
}
