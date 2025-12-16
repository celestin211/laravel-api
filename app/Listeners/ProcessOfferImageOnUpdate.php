<?php

namespace App\Listeners;

use App\Events\OfferUpdated;
use App\Jobs\ProcessImageJob;

/**
 * Listener to dispatch image processing job when an offer image is updated.
 */
class ProcessOfferImageOnUpdate
{
    /**
     * Handle the event.
     */
    public function handle(OfferUpdated $event): void
    {
        // Only process if image was changed
        if ($event->offer->wasChanged('image') && $event->offer->image !== null) {
            ProcessImageJob::dispatch($event->offer->image, 'offers')
                ->onQueue('images');
        }
    }
}
