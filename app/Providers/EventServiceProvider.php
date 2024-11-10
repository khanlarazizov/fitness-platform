<?php

namespace App\Providers;

use App\Events\PlanAssigned;
use App\Events\UserRegistered;
use App\Listeners\SendAssignedPlanMailForUser;
use App\Listeners\SendWelcomeMailForUser;
use App\Listeners\SendWelcomeMailForAdmin;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserRegistered::class => [
            SendWelcomeMailForUser::class,
            SendWelcomeMailForAdmin::class
        ],
        PlanAssigned::class => [
            SendAssignedPlanMailForUser::class
        ]
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
