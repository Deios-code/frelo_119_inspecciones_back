<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisteredUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $datos = [];

    public function __construct($datos)
    {
        $this->datos = $datos;
    }

    public function envelope()
    {
        return new Envelope(
            subject: '¡Bienvenido! – Credenciales de Acceso a tu Cuenta',
        );
    }

    public function content()
    {
        return new Content(
            view: 'Mails.RegisteredUser',
        );
    }
}
