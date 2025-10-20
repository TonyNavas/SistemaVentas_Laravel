<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class NotificationBadge extends Component
{
    public int $count = 0;
    public int $userId;

    public function mount()
    {
        $this->userId = auth()->id();
        // lee de DB, no de auth()->user() cacheado
        $this->count = User::find($this->userId)->notification ?? 0;
    }

    // Escucha notificaciones broadcast y un evento interno para reset
    public function getListeners()
    {
        return [
            "echo-notification:App.Models.User.{$this->userId}" => 'onNotify',
            'notifications-reset' => 'resetBadge',
        ];
    }

    public function onNotify($payload)
    {
        $this->count++; // llegó una nueva notificación
    }

    public function resetBadge()
    {
        $this->count = 0;
    }
    public function render()
    {
        return view('livewire.notification-badge');
    }
}
