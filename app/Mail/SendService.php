<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendService extends Mailable
{
    use Queueable, SerializesModels;
    public $service;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.send_service');
    }
}
