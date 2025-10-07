<?php

use App\Models\Table;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('orders.{token}', function ($user = null, $token) {
    return Table::where('token', $token)->exists();
});

