<?php

namespace App\Livewire\Mesas;

use App\Models\Order;
use Livewire\Component;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Gate;

class OrderDetailComponent extends Component
{
    public $orderId;
    public $details = [];

    public function mount($id)
    {
        $this->orderId = $id;
    }

    public function openModal()
    {
        // Cargar los detalles de la orden al abrir el modal
        $order = Order::with('details')->find($this->orderId);

        if ($order) {
            $this->details = $order->details;
        }
        $this->dispatch('open-modal', 'modalOrderDetail-'.$this->orderId);
    }
    public function render()
    {
        return view('livewire.mesas.order-detail-component');
    }
}
