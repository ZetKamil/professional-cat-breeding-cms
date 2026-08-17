<?php

namespace App\Listeners;

use App\Events\ContactMessageSent;
use App\Notifications\ContactMessageNotification;
use Illuminate\Support\Facades\Notification;

class SendContactMessageMail
{
    public function handle(ContactMessageSent $event): void
    {
        $recipient = env('MAIL_CONTACT_RECIPIENT', config('mail.from.address', 'biuro@kotyzmazowieckiejszwajcarii.pl'));

        Notification::route('mail', $recipient)
            ->notify(new ContactMessageNotification($event->data));
    }
}
