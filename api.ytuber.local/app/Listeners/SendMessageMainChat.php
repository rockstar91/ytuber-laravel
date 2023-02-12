<?php

namespace App\Listeners;

use App\Events\PrivateChat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMessageMainChat
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\PrivateChat  $event
     * @return void
     */
    public function handle(PrivateChat $event)
    {
        //
    }
}
