<?php

namespace App\Providers;

use App\Domain\Repositories\OfferRepositoryInterface;
use App\Domain\Repositories\ProductRepositoryInterface;
use App\Models\Offer;
use App\Models\Product;
use App\Observers\OfferObserver;
use App\Observers\ProductObserver;
use App\Repositories\OfferRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind repository interfaces to implementations (Ports & Adapters pattern)
        $this->app->bind(OfferRepositoryInterface::class, OfferRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default string length for MySQL/MariaDB utf8mb4 compatibility
        // MySQL has a limit of 1000 bytes for indexes, and utf8mb4 uses 4 bytes per character
        // 191 characters * 4 bytes = 764 bytes, which is under the limit
        Schema::defaultStringLength(191);

        // Register observers
        if (class_exists(Offer::class)) {
            Offer::observe(OfferObserver::class);
        }
        Product::observe(ProductObserver::class);
    }
}
