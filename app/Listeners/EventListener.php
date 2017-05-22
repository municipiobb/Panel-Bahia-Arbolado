<?php

namespace App\Listeners;

use App\Acme\Helpers;
use App\LoginTrack;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventListener
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
     * @param Login $event
     * @return void
     */
    public function handle(Login $event)
    {
        LoginTrack::create([
            'ip' => Helpers::obtenerIP(),
            'user_id' => $event->user->id
        ]);
    }
}
