<?php

namespace App\Livewire\Home;

use App\Models\Item;
use App\Models\OrderDetail;
use App\Models\Sale;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Inicio')]
class Inicio extends Component
{
    public $ventasHoy = 0;
    public $totalventasHoy = 0;
    public $articulosHoy = 0;
    public $productosHoy = 0;

    public $listTotalVentasMes = '';

    public function sales_today()
    {
        $today = date('Y-m-d');

        $this->ventasHoy = Sale::whereDate('fecha', $today)->count();
        $this->totalventasHoy = Sale::whereDate('fecha', $today)->sum('total');
        $this->articulosHoy = OrderDetail::whereDate('created_at', $today)->sum('quantity');
        $this->productosHoy = OrderDetail::whereDate('created_at', $today)->distinct('product_id')->count('product_id');
    }

    public function VentasMes() {
        for ($i=1; $i < 12; $i++) {
            $this->listTotalVentasMes .= Sale::whereMonth('fecha', '=', $i)->sum('total').',';
        }
    }


    public function render()
    {
        $this->sales_today();
        $this->VentasMes();

        return view('livewire.home.inicio');
    }
}
