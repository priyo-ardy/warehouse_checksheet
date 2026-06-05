<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;

class HandleUserLoginAttempts
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
    public function handle(object $event): void
    {
        if ($event instanceof Failed) {
            if ($event->user instanceof User) {
                $user = $event->user;

                $user->login_attempts = ($user->login_attempts ?? 0) + 1;

                if ($user->login_attempts >= 5) {
                    $user->is_locked = true;
                }

                $user->save();
            }
        }

        if ($event instanceof Login) {
            if ($event->user instanceof User) {
                $user = $event->user;

                $user->login_attempts = 0;
                $user->is_locked = false;
                $user->last_login = now();
                $user->last_login_from = request()->ip();
                $user->user_agent = request()->userAgent();

                $user->save();
            }
        }
    }
}
