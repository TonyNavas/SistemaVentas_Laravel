<?php

namespace App\Livewire;

use Livewire\Component;

class Notifications extends Component
{
    public $count = 8;
    public int $userId;

    public function mount(){
         $this->userId = auth()->id();
    }

        public function getListeners()
    {
        return [
            "echo-notification:App.Models.User.{$this->userId}" => 'onNotify',
        ];
    }

    public function onNotify($payload)
    {
        $this->dispatch('$refresh');
    }

    public function getNotificationsProperty()
    {
        return auth()->user()
            ->notifications()
            ->latest() // ordena de más recientes a más antiguas
            ->take($this->count)
            ->get();
    }

    public function readNotification($id)
    {
        auth()->user()->notifications->find($id)->markAsRead();
    }

    public function resetNotification()
    {
        auth()->user()->notification = 0;
        auth()->user()->save();
    }

    public function incrementCount()
    {
        $this->count += 1;
    }


    public function render()
    {
        return view('livewire.notifications');
    }
}
