<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nowa wiadomość z formularza kontaktowego')
            ->greeting('Otrzymano nową wiadomość z formularza na stronie.')
            ->line('Imię / nazwisko: '.$this->data['name'])
            ->line('E-mail: '.$this->data['email'])
            ->line('Treść wiadomości:')
            ->line($this->data['message']);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
