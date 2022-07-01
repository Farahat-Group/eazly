<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    private $title;
    private $email;
    private $password;



    public function __construct($title , $email , $password)
    {
        $this->title = $title;
        $this->email = $email;
        $this->password = $password;
    }


    public function build()
    {
        return $this->subject($this->title)
          ->view('mailtemplate')->with([
                'email' => $this->email,
                'password' => $this->password
            ]);
    }
}
