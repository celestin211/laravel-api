<?php

namespace App\Listeners;

use App\Events\OfferCreated;
use Illuminate\Support\Facades\Log;

/**
 * Listener to log when an offer is created.
 */
class LogOfferCreated
{
    /**
     * Handle the event.
     */
    public function handle(OfferCreated $event): void
    {
        Log::info('Offer created', [
            'offer_id' => $event->offer->id,
            'offer_name' => $event->offer->name,
            'offer_slug' => $event->offer->slug,
            'state' => $event->offer->state,
        ]);
    }
}
