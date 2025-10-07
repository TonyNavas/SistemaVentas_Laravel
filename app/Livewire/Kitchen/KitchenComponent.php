<?php

namespace App\Livewire\Kitchen;

use App\Events\ChangeOrderStatus;
use App\Models\Order;
use Livewire\Component;
use App\Models\OrderDetail;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

#[Title('Cocina')]
class KitchenComponent extends Component
{
    public $orders;
    public $PendingOrders;
    public $pendingProducts;

    public function mount()
    {
        $this->getOrders();
        $this->totalOrders();
        $this->totalPendingProducts();
    }

    #[On('echo:orders,CreateOrder')]
    public function getOrders(){
        $this->orders = Order::whereIn('status', ['nuevo', 'en_proceso', 'listo'])->get();
    }

    public function totalOrders()
    {
        $this->PendingOrders = Order::whereIn('status', ['nuevo', 'en_proceso', 'listo'])->count();
    }
    public function totalPendingProducts()
    {
        $this->pendingProducts = OrderDetail::whereHas('order', function ($query) {
            $query->whereIn('status', ['nuevo', 'en_proceso', 'listo']);
        })->sum('quantity');
    }

        public function chancheProductStatus($detailId)
    {
        $orderDetail = OrderDetail::find($detailId);
        if($orderDetail->status == "nuevo"){
            $orderDetail->status = 'en_proceso';
        }elseif ($orderDetail->status == "en_proceso"){
            $orderDetail->status = 'listo';
        }elseif($orderDetail->status = 'listo'){
            $orderDetail->status = 'entregado';
        }
        $orderDetail->save();

        $this->getOrders();
    }

public function chancheOrderStatus($orderId)
{
    $order = Order::findOrFail($orderId);

    $estados = ['nuevo', 'en_proceso', 'listo', 'entregado'];
    $indice = array_search($order->status, $estados);

    if ($indice !== false && $indice < count($estados) - 1) {
        $order->status = $estados[$indice + 1];
        $order->save();

        // Si la orden llega al estado "entregado", actualizamos los productos
        if ($order->status === 'entregado') {
            $order->details()->update(['status' => 'entregado']);
        }

        // Emitimos el evento para notificar a la mesa
        ChangeOrderStatus::dispatch($order, $order->table->token);

        $this->getOrders();
    }
}



    public function render()
    {
        return view('livewire.kitchen.kitchen-component');
    }
}
