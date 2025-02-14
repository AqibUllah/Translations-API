<?php

namespace App\Providers;

use App\Repositories\CacheTranslationRepository;
use App\Repositories\Contracts\TranslationRepositoryInterface;
use App\Repositories\EloquentTranslationRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Binding interface with Eloquent implementation
        $this->app->bind(
            TranslationRepositoryInterface::class,
            EloquentTranslationRepository::class
        );

        // caching decorator
        $this->app->bind(
            TranslationRepositoryInterface::class,
            function ($app) {
                return new CacheTranslationRepository($app->make(EloquentTranslationRepository::class), 500);
            }
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
