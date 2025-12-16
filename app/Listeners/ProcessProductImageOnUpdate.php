<?php

namespace App\Listeners;

use App\Events\ProductUpdated;
use App\Jobs\ProcessImageJob;

/**
 * Listener to dispatch image processing job when a product image is updated.
 */
class ProcessProductImageOnUpdate
{
    /**
     * Handle the event.
     */
    public function handle(ProductUpdated $event): void
    {
        // Only process if image was changed
        if ($event->product->wasChanged('image') && $event->product->image !== null) {
            ProcessImageJob::dispatch($event->product->image, 'products')
                ->onQueue('images');
        }
    }
}
