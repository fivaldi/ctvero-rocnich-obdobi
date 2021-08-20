<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($from, $subject, $message)
    {
        $this->fromAddress = $from;
        $this->subj = $subject;
        $this->msg = $message;
    }

    public function build()
    {
        $subjPrefix = '[' . config('app.name') . '] ';
        return $this->subject($subjPrefix.$this->subj)->markdown('emails.message')->with([
            'from' => $this->fromAddress,
            'message' => $this->msg
        ]);
    }
}
