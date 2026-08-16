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
        $subject = ! empty($this->data['subject'])
            ? 'Zapytanie: ' . $this->data['subject']
            : 'Nowa wiadomość z formularza kontaktowego';

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting('Otrzymano nową wiadomość z formularza na stronie.')
            ->line('Imię i nazwisko: ' . ($this->data['name'] ?? '—'))
            ->line('Adres e-mail: ' . ($this->data['email'] ?? '—'));

        if (! empty($this->data['phone'])) {
            $mail->line('Numer telefonu: ' . $this->data['phone']);
        }

        if (! empty($this->data['subject'])) {
            $mail->line('Temat: ' . $this->data['subject']);
        }

        $mail->line('Treść wiadomości:')
            ->line($this->data['message']);

        if (! empty($this->data['email'])) {
            $mail->replyTo($this->data['email'], $this->data['name'] ?? null);
        }

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
