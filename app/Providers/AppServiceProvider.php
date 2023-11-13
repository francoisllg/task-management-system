<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \Src\Task\Domain\Interface\TaskRepositoryInterface::class,
            \Src\Task\Infraestructure\Repository\EloquentTaskRepository::class
        );

        $this->app->bind(
            \Src\User\Domain\Interface\UserRepositoryInterface::class,
            \Src\User\Infraestructure\Repository\EloquentUserRepository::class
        );

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
