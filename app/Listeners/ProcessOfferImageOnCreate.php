<?php

namespace App\Listeners;

use App\Events\OfferCreated;
use App\Jobs\ProcessImageJob;

/**
 * Listener to dispatch image processing job when an offer is created with an image.
 */
class ProcessOfferImageOnCreate
{
    /**
     * Handle the event.
     */
    public function handle(OfferCreated $event): void
    {
        if ($event->offer->image !== null) {
            ProcessImageJob::dispatch($event->offer->image, 'offers')
                ->onQueue('images');
        }
    }
}
