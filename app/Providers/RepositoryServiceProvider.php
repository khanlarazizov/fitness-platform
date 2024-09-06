<?php

namespace App\Providers;

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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
