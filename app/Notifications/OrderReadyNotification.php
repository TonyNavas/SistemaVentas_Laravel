<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class OrderReadyNotification extends Notification implements ShouldQueue
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
     * Get the mail representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {

        $notifiable->notification +=1;
        $notifiable->save();

        return [
            'url' => $this->url,
            'message' => 'Pedido listo para entregar '.'(orden#'.$this->order->id.')'.'para la mesa '. $this->order->table->code,
            'title' => 'Pedido listo!',
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([]);
    }
}
