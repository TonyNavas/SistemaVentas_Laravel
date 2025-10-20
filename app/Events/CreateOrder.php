<?php

namespace App\Events;

use Log;
use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateOrder implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $order;
    public $mesaToken;

    public function __construct(Order $order, $mesaToken)
    {
        $this->order = $order;
        $this->mesaToken = $mesaToken;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
        new PrivateChannel('orders.' . $this->mesaToken), // solo esa mesa
        new Channel('orders'), // canal global para cocina
        ];
    }
}
