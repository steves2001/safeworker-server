<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactDepartment extends Mailable
{
    use Queueable, SerializesModels;
    
    public $userEmail;
    public $userMessage;
    public $userName;
    public $contactEmail;
    public $userId;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $message, $name, $contact)
    {
        $this->userEmail = $email;
        $this->userMessage = $message;
        $this->userName = $name;
        $this->contactEmail = $contact;
        $emailDetails = explode('@', $email, 3);
        $this->userId = $emailDetails[0];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->userEmail)->subject('Student Enquiry - [' . $this->userId . '] ' . $this->userName)->view('emails.contactdepartment');
    }
}
