<?php

namespace App\Livewire\Kitchen;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Title;
use App\Events\ChangeOrderStatus;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderReadyNotification;

#[Title('Pedidos listos')]
class OrdersReady extends Component
{
    public $orders;

    public function mount(){
        $this->getOrders();
    }

    public function getOrders()
    {
        $this->orders = Order::whereIn('status', ['listo'])->get();
    }

        public function chancheOrderStatus($orderId)
    {
        Gate::authorize('cambiar-estado-orden-lista');
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
        return view('livewire.kitchen.orders-ready');
    }
}
