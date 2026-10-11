<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ContactMail extends Mailable
{
    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject('Nowa wiadomość z formularza kontaktowego')
            ->view('emails.contact');
    }
}
