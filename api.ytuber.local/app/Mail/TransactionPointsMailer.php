<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use stdClass;

class TransactionPointsMailer extends Mailable
{
    use Queueable, SerializesModels;

    // User data.

    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(stdClass $data) {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->from('info@ytuber.ru', $this->data->email)
            ->subject('Ссылка для подтверждения от сайта ytuber.ru')
            ->view('email.transaction_accept', ['data' => $this->data]);
    }
}