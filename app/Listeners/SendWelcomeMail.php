<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\WelcomeMailForAdmin;
use App\Mail\WelcomeMailForUser;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeMail
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
        Mail::to($event->user->email)->send(new WelcomeMailForUser($event->user));

        $admins = User::whereHas('roles', fn($query) => $query->where('name', 'admin'))->get();

        Mail::to($admins)->send(new WelcomeMailForAdmin($event->user));
    }
}
