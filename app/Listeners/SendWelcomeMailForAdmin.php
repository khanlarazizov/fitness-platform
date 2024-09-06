<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\WelcomeMailForAdmin;
use App\Mail\WelcomeNewUserForAdmin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeMailForAdmin
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
    public function handle(UserRegistered $event): void
    {
        Mail::to('zOQpY@example.com')->send(new WelcomeMailForAdmin($event->user));
    }
}
