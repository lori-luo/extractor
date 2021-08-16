<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LoginListener
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if (!$event->user->email == "richard@importer.com") {
            $event->user->logs()->create([
                'action' => 'Login',
                'type' => 'login',
                'obj' => ''
            ]);
        }
    }
}
