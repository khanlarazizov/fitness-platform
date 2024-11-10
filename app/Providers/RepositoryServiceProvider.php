<?php

namespace App\Providers;

use App\Lib\CategoryRepository;
use App\Lib\Interfaces\ICategoryRepository;
use App\Lib\Interfaces\IPlanRepository;
use App\Lib\Interfaces\IWorkoutRepository;
use App\Lib\PlanRepository;
use App\Lib\TrainerPlanRepository;
use App\Lib\WorkoutRepository;
use Illuminate\Support\ServiceProvider;
use App\Lib\Interfaces\IUserRepository;
use App\Lib\UserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(ICategoryRepository::class, CategoryRepository::class);
        $this->app->bind(IWorkoutRepository::class, WorkoutRepository::class);
        $this->app->bind(IPlanRepository::class, PlanRepository::class);
        $this->app->bind(PlanRepository::class, TrainerPlanRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
