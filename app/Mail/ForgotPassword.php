<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verify_code;

    public function __construct($check_email, $verify_code)
    {
        $this->user = $check_email;
        $this->verify_code = $verify_code;
    }

    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))->view('emails.forgot-password', [
            'user'          =>  $this->user,
            'verify_code'   =>  $this->verify_code,
        ]);
    }
}
