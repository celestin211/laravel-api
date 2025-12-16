<?php

namespace App\Listeners;

use App\Events\OfferUpdated;
use Illuminate\Support\Facades\Log;

/**
 * Listener to log when an offer is updated.
 */
class LogOfferUpdated
{
    /**
     * Handle the event.
     */
    public function handle(OfferUpdated $event): void
    {
        Log::info('Offer updated', [
            'offer_id' => $event->offer->id,
            'offer_name' => $event->offer->name,
            'offer_slug' => $event->offer->slug,
            'state' => $event->offer->state,
        ]);
    }
}
