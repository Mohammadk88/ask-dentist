<?php

namespace App\Providers;

use App\Events\TreatmentPlanSubmitted;
use App\Listeners\CancelCompetingPlans;
use App\Domain\Events\PlanAccepted;
use App\Domain\Handlers\PlanAcceptedHandler;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        TreatmentPlanSubmitted::class => [
            CancelCompetingPlans::class,
        ],
        PlanAccepted::class => [
            PlanAcceptedHandler::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}