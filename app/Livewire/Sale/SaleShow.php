<?php

namespace App\Livewire\Sale;

use App\Models\Sale;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detalle venta')]
class SaleShow extends Component
{
    public Sale $sale;

    public function render()
    {
        Gate::authorize('ver-ventas');
        return view('livewire.sale.sale-show');
    }
}
