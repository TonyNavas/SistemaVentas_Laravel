<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\Table;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;
    public $url;
    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, $url)
    {
        $this->order = $order;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {

        $notifiable->notification +=1;
        $notifiable->save();

        return [
            'url' => $this->url,
            'message' => 'Hay un nuevo pedido '.'(orden#'.$this->order->id.')'.' en cocina, de la cabaña '. $this->order->table->code,
            'title' => 'Nuevo pedido!',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([]);
    }
}
