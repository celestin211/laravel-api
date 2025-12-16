<?php

namespace App\Listeners;

use App\Events\OfferDeleted;
use Illuminate\Support\Facades\Log;

/**
 * Listener to log when an offer is deleted.
 */
class LogOfferDeleted
{
    /**
     * Handle the event.
     */
    public function handle(OfferDeleted $event): void
    {
        Log::info('Offer deleted', [
            'offer_id' => $event->offer->id,
            'offer_name' => $event->offer->name,
            'offer_slug' => $event->offer->slug,
        ]);
    }
}
