<?php

namespace App\Livewire\Sale;

use App\Models\Product;
use App\Models\Sale;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Listado de ventas')]
class SaleList extends Component
{
    use WithPagination;

    // Propiedades clase
    public $search = '';
    public $SalesCount = 0;
    public $pagination = 10;

    public $totalVentas = 0;
    public $dateInicio;
    public $dateFin;

    public function mount()
    {
        $this->SalesCount();
    }

    public function SalesCount()
    {
        $this->SalesCount = Sale::count();
    }

    #[On('destroySale')]
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);

        foreach ($sale->items as $item) {
            // Devolver stock al producto asociado
            Product::find($item->id)->increment('stock', $item->qty);
            // Eliminar el detalle (item)
            $item->delete();
        }

        // Finalmente eliminar la venta
        $sale->delete();

        $this->dispatch('msg', 'Venta eliminada');
    }

    #[On('setDates')]
    public function setDates($fechaInicio, $fechaFinal)
    {

        $this->dateInicio = $fechaInicio;
        $this->dateFin = $fechaFinal;
    }


    public function render()
    {
        if ($this->search != '') {
            $this->resetPage();
        }
        $salesQuery = Sale::where('id', 'LIKE', '%' . $this->search . '%');

        if ($this->dateInicio && $this->dateFin) {
            $salesQuery = $salesQuery->whereBetween('fecha', [$this->dateInicio, $this->dateFin]);

            $this->totalVentas = $salesQuery->sum('total');
        } else {
            $this->totalVentas = Sale::sum('total');
        }

        $sales = $salesQuery->orderBy('id', 'desc')
            ->paginate($this->pagination);

        return view('livewire.sale.sale-list', ["sales" => $sales]);
    }
}
