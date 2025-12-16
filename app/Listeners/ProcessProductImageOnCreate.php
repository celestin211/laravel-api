<?php

namespace App\Listeners;

use App\Events\ProductCreated;
use App\Jobs\ProcessImageJob;

/**
 * Listener to dispatch image processing job when a product is created with an image.
 */
class ProcessProductImageOnCreate
{
    /**
     * Handle the event.
     */
    public function handle(ProductCreated $event): void
    {
        if ($event->product->image !== null) {
            ProcessImageJob::dispatch($event->product->image, 'products')
                ->onQueue('images');
        }
    }
}
