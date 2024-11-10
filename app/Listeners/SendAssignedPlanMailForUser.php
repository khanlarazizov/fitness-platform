<?php

namespace App\Listeners;

use App\Events\PlanAssigned;
use App\Mail\AssignedPlanForUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendAssignedPlanMailForUser
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
    public function handle(PlanAssigned $event): void
    {
        Mail::to($event->user->email)->send(new AssignedPlanForUser($event->user));
    }
}