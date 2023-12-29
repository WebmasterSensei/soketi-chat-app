<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        //
         $user_update = $event->user;

        $user_update->is_active = true;
        $user_update->is_logoff = true;
        
        $user_update->update();

        // /Log::
         Log::info('User logged in: ' . $user_update->name . ' (ID: ' . $user_update->id . ')');
    }
}
