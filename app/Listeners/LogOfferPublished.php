<?php

namespace App\Listeners;

use App\Events\OfferPublished;
use Illuminate\Support\Facades\Log;

/**
 * Listener to log when an offer is published.
 */
class LogOfferPublished
{
    /**
     * Handle the event.
     */
    public function handle(OfferPublished $event): void
    {
        Log::info('Offer published', [
            'offer_id' => $event->offer->id,
            'offer_name' => $event->offer->name,
            'offer_slug' => $event->offer->slug,
        ]);
    }
}
