<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;
    
    public $userToken;
    public $userName;
    public $apiRoute = 'https://toast-canvas-3000.codio.io/api/register/confirm/';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, $name)
    {
        $this->userToken = $token;
        $this->userName = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.registrationconfirmation');
    }
}
