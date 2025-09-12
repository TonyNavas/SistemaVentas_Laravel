<?php

namespace App\Livewire\Kitchen;

use App\Models\Order;
use Livewire\Component;
use App\Models\OrderDetail;
use Livewire\Attributes\Title;

#[Title('Cocina')]
class KitchenComponent extends Component
{
    public $orders;
    public $PendingOrders;
    public $pendingProducts;

    public function mount()
    {
        $this->orders = Order::whereIn('status', ['nuevo', 'en_proceso', 'listo'])->get();
        $this->totalOrders();
        $this->totalPendingProducts();
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

        $this->orders = Order::whereIn('status', ['nuevo', 'en_proceso', 'listo'])->get();
    }

            public function chancheOrderStatus($orderId)
    {
        $order = Order::find($orderId);
        if($order->status == "nuevo"){
            $order->status = 'en_proceso';
        }elseif ($order->status == "en_proceso"){
            $order->status = 'listo';
        }elseif($order->status = 'listo'){
            $order->status = 'entregado';
        }
        $order->save();

        $this->orders = Order::whereIn('status', ['nuevo', 'en_proceso', 'listo'])->get();
    }

    public function render()
    {
        return view('livewire.kitchen.kitchen-component');
    }
}
