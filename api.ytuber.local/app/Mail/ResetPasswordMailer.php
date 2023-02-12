<?php

namespace App\Mail;

use stdClass;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMailer extends Mailable
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
        return $this->from('info@ytuber.ru', 'ytuber.ru')
            ->subject('Сброс пароля на сайте')
            ->view('email.resetpass', ['data' => $this->data]);
    }
}
