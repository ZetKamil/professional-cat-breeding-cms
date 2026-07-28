<?php

namespace App\Providers;

use App\Events\ContactMessageSent;
use App\Events\PostCreated;
use App\Events\PostUpdated;
use App\Listeners\LogPostCreated;
use App\Listeners\LogPostUpdated;
use App\Listeners\SendContactMessageMail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ContactMessageSent::class => [
            SendContactMessageMail::class,
        ],
        PostCreated::class => [
            LogPostCreated::class,
        ],
        PostUpdated::class => [
            LogPostUpdated::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
