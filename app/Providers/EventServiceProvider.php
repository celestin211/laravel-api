<?php

namespace App\Providers;

use App\Events\OfferCreated;
use App\Events\OfferDeleted;
use App\Events\OfferPublished;
use App\Events\OfferUpdated;
use App\Events\ProductCreated;
use App\Events\ProductDeleted;
use App\Events\ProductUpdated;
use App\Listeners\DetectOfferStateChange;
use App\Listeners\LogOfferCreated;
use App\Listeners\LogOfferDeleted;
use App\Listeners\LogOfferPublished;
use App\Listeners\LogOfferUpdated;
use App\Listeners\NotifyAdminOfferPublished;
use App\Listeners\ProcessOfferImageOnCreate;
use App\Listeners\ProcessOfferImageOnUpdate;
use App\Listeners\ProcessProductImageOnCreate;
use App\Listeners\ProcessProductImageOnUpdate;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        OfferCreated::class => [
            LogOfferCreated::class,
            ProcessOfferImageOnCreate::class,
        ],
        OfferUpdated::class => [
            LogOfferUpdated::class,
            ProcessOfferImageOnUpdate::class,
            DetectOfferStateChange::class,
        ],
        OfferDeleted::class => [
            LogOfferDeleted::class,
        ],
        OfferPublished::class => [
            LogOfferPublished::class,
            NotifyAdminOfferPublished::class,
        ],
        ProductCreated::class => [
            ProcessProductImageOnCreate::class,
        ],
        ProductUpdated::class => [
            ProcessProductImageOnUpdate::class,
        ],
        ProductDeleted::class => [
            // Add listeners here if needed
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
