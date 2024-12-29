<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ErrorOccurredMail extends Mailable
{
    use SerializesModels;

    // Los datos que se pasarán al correo
    public array $errorData;

    /**
     * Crear una nueva instancia de mensaje.
     *
     * @param  array  $errorData
     * @return void
     */
    public function __construct(array $errorData)
    {
        $this->errorData = $errorData;
    }

    /**
     * Construir el mensaje.
     *
     * @return Mailable
     */
    public function build(): Mailable
    {
        return $this->subject('Error Occurred in Application')
            ->view('emails.error_occurred');
    }
}
