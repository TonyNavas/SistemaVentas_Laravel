<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
// app/Providers/BroadcastServiceProvider.php
public function boot(): void
{
    Broadcast::routes([
        'middleware' => ['web'], // ✅ CSRF y cookies, pero sin auth
    ]);

    require base_path('routes/channels.php');
}

}
