<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactMessageNotification extends Notification
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
        $rawSubject = ! empty($this->data['subject'])
            ? 'Zapytanie: ' . $this->data['subject']
            : 'Nowa wiadomość z formularza kontaktowego';

        // Strip CRLF to prevent mail header injection
        $sanitizedSubject = str_replace(["\r", "\n", "\t"], ' ', strip_tags($rawSubject));
        $sanitizedName = ! empty($this->data['name']) ? str_replace(["\r", "\n", "\t"], ' ', strip_tags($this->data['name'])) : null;
        $sanitizedEmail = ! empty($this->data['email']) ? str_replace(["\r", "\n", "\t"], '', strip_tags($this->data['email'])) : null;
        $sanitizedPhone = ! empty($this->data['phone']) ? str_replace(["\r", "\n", "\t"], ' ', strip_tags($this->data['phone'])) : null;

        $mail = (new MailMessage)
            ->subject($sanitizedSubject)
            ->greeting('Otrzymano nową wiadomość z formularza na stronie.')
            ->line('Imię i nazwisko: ' . ($sanitizedName ?? '—'))
            ->line('Adres e-mail: ' . ($sanitizedEmail ?? '—'));

        if (! empty($sanitizedPhone)) {
            $mail->line('Numer telefonu: ' . $sanitizedPhone);
        }

        if (! empty($this->data['subject'])) {
            $mail->line('Temat: ' . $sanitizedSubject);
        }

        $mail->line('Treść wiadomości:')
            ->line($this->data['message']);

        if (! empty($sanitizedEmail)) {
            $mail->replyTo($sanitizedEmail, $sanitizedName);
        }

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
