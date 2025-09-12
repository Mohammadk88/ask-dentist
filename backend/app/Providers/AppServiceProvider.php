<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerRepositories();
        $this->registerServices();
    }

    /**
     * Register repository bindings
     */
    protected function registerRepositories(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\StoryRepositoryInterface::class,
            \App\Repositories\StoryRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\ClinicRepositoryInterface::class,
            \App\Repositories\ClinicRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\DoctorRepositoryInterface::class,
            \App\Repositories\DoctorRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\FavoritesRepositoryInterface::class,
            \App\Repositories\FavoritesRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\BeforeAfterRepositoryInterface::class,
            \App\Repositories\BeforeAfterRepository::class
        );
    }

    /**
     * Register service bindings
     */
    protected function registerServices(): void
    {
        $this->app->singleton(
            \App\Services\MediaService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
        $this->registerObservers();
        $this->configureMorphMaps();
    }

    /**
     * Configure morph maps for polymorphic relationships
     */
    protected function configureMorphMaps(): void
    {
        \Illuminate\Database\Eloquent\Relations\Relation::morphMap([
            'doctor' => \App\Models\Doctor::class,
            'clinic' => \App\Models\Clinic::class,
        ]);
    }

    /**
     * Register model observers
     */
    protected function registerObservers(): void
    {
        \App\Models\Review::observe(\App\Observers\ReviewObserver::class);
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('password-reset', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        RateLimiter::for('verification', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });
    }
}
