<?php

namespace App\Providers;

use App\Events\Owners\SuspendStatusEvent;
use App\Listeners\Owners\SendSuspendNotification;
use App\Listeners\Owners\StartSuspendTimer;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SuspendStatusEvent::class => [
            StartSuspendTimer::class,
            SendSuspendNotification::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
