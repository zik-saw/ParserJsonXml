<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\CarRepository;
use App\Repositories\CarRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            CarRepositoryInterface::class,
            CarRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
    }
}
