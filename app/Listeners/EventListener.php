<?php

namespace App\Listeners;

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
     * @param  Login $user
     * @return void
     */
    public function handle(Login $event)
    {
        \App\LoginTrack::create([
            'ip' => request()->ip(),
            'user_id' => $event->user->id
        ]);
    }
}
