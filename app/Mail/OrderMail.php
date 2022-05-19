<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $order, $orderDetails)
    {
        $this->user = $user;
        $this->order = $order;
        $this->orderDetails = $orderDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      
        return $this->from('techcitynotification@gmail.com')
            ->to($this->user['email'])
            ->subject('Order Information')
            ->view('emails.mail-order-notify')->with('data', [
                'user' => $this->user,
                'order' => $this->order,
                'orderDetails' => $this->orderDetails
            ]);
    }
}
