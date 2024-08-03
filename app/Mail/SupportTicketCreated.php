<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupportTicketCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $support, $user_name;

    public function __construct($support, $user_name)
    {
        $this->support = $support;
        $this->user_name = $user_name;
    }

    public function build()
    {
        return $this->subject('New Support Ticket Created')
                    ->view('emails.support_ticket_created')
                    ->with(['support', $this->support, $this->user_name]);
    }
}
