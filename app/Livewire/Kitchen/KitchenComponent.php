<?php

namespace App\Livewire\Kitchen;

use App\Models\User;
use App\Models\Order;
use Livewire\Component;
use App\Models\OrderDetail;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use App\Events\ChangeOrderStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderReadyNotification;

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
    public function getOrders()
    {
        $this->orders = Order::whereIn('status', ['nuevo', 'en_proceso'])->get();

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
        Gate::authorize('cambiar-estado-productos');
        $orderDetail = OrderDetail::find($detailId);

        $estados = ['nuevo', 'en_proceso', 'listo', 'entregado'];
        $indice = array_search($orderDetail->status, $estados);

        if ($indice !== false && $indice < count($estados) - 1) {
            $orderDetail->status = $estados[$indice + 1];
            $orderDetail->save();

            $this->getOrders();
            $this->totalPendingProducts();
        }

    }

    public function chancheOrderStatus($orderId)
    {
        Gate::authorize('cambiar-estado-orden');
        $order = Order::findOrFail($orderId);

        $estados = ['nuevo', 'en_proceso', 'listo', 'entregado'];
        $indice = array_search($order->status, $estados);

        if ($indice !== false && $indice < count($estados) - 1) {
            $order->status = $estados[$indice + 1];
            $order->save();

            // Si la orden llega al estado "listo", enviamos evento

            $users = User::role(['mesero', 'administrador'])
                ->where('id', '!=', Auth::user()->id)
                ->get();

            $url = route('ordersready.list');

            if ($order->status === 'listo') {
                $order->details()->update(['status' => 'listo']);
                Notification::send($users, new OrderReadyNotification($order, $url));
            }

            // Si la orden llega al estado "entregado", actualizamos los productos
            if ($order->status === 'entregado') {
                $order->details()->update(['status' => 'entregado']);
            }

            // Emitimos el evento para notificar a la mesa
            ChangeOrderStatus::dispatch($order, $order->table->token);

            $this->getOrders();
            $this->totalOrders();
            $this->totalPendingProducts();
        }
    }



    public function render()
    {
        Gate::authorize('ver-cocina');
        return view('livewire.kitchen.kitchen-component');
    }
}
