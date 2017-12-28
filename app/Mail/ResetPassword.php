<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;
    
    public $userPassword;
    public $userToken;
    public $userName;
    public $apiRoute = 'https://toast-canvas-3000.codio.io/api/password/confirm/';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($password, $token, $name)
    {
        $this->userPassword = $password;
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
        return $this->view('emails.resetpassword');
    }
}
