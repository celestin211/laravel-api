<?php

namespace App\Listeners;

use App\Events\OfferUpdated;
use App\Events\OfferPublished;

/**
 * Listener to detect when an offer state changes to 'published'.
 */
class DetectOfferStateChange
{
    /**
     * Handle the event.
     */
    public function handle(OfferUpdated $event): void
    {
        // Check if state changed to 'published'
        if ($event->offer->wasChanged('state') && $event->offer->state === 'published') {
            event(new OfferPublished($event->offer));
        }
    }
}
