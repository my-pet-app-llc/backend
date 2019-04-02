<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $isApiRequest = request()->is('api/*');
        $attributes = !$isApiRequest ? ['middleware' => 'auth:api'] : [];

        Broadcast::routes($attributes);


        require base_path('routes/channels.php');
    }
}
