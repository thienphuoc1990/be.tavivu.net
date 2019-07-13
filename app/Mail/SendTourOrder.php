<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTourOrder extends Mailable
{
    use Queueable, SerializesModels;
    public $order_tour;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order_tour)
    {
        $this->order_tour = $order_tour;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.send_tour_order');
    }
}
