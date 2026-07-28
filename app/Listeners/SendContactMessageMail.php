<?php

namespace App\Listeners;

use App\Events\ContactMessageSent;
use App\Notifications\ContactMessageNotification;
use Illuminate\Support\Facades\Notification;

class SendContactMessageMail
{
    public function handle(ContactMessageSent $event): void
    {
        Notification::route('mail', 'syntraprogrammeurs@gmail.com')
            ->notify(new ContactMessageNotification($event->data));
    }
}
