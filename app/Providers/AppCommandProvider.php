<?php

declare(strict_types=1);

namespace App\Providers;

use App\Factories\ParserFactory;
use App\Factories\ParserFactoryInterface;
use Illuminate\Support\ServiceProvider;

class AppCommandProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            ParserFactoryInterface::class,
            ParserFactory::class
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
