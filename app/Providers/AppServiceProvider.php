<?php

namespace App\Providers;

use App\Observers\OwnerObserver;
use App\Observers\UserObserver;
use App\Owner;
use App\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Owner::observe(OwnerObserver::class);
        User::observe(UserObserver::class);
    }
}
