<?php

namespace CodeShopping\Listerners;

use CodeShopping\Events\UserCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Password;

class SendMailToDefinePassword
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
     * @param  UserCreatedEvent $event
     * @return void
     */
    public function handle(UserCreatedEvent $event)
    {
        /** @var User $user */
        $user = $event->getUser();
        $token = Password::broker()->createToken($user);

        $user->sendPasswordResetNotification($token);
    }
}
