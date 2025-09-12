<?php

namespace App\Livewire\Sale;

use App\Models\Sale;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detalle venta')]
class SaleShow extends Component
{
    public Sale $sale;

    public function render()
    {
        dump($this->sale);
        return view('livewire.sale.sale-show');
    }
}
