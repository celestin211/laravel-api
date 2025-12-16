<?php

namespace App\Observers;

use App\Events\OfferCreated;
use App\Events\OfferDeleted;
use App\Events\OfferUpdated;
use App\Models\Offer;

/**
 * Observer for Offer model.
 * Dispatches events when offers are created, updated, or deleted.
 */
class OfferObserver
{
    /**
     * Handle the Offer "created" event.
     */
    public function created(Offer $offer): void
    {
        event(new OfferCreated($offer));
    }

    /**
     * Handle the Offer "updated" event.
     */
    public function updated(Offer $offer): void
    {
        event(new OfferUpdated($offer));
    }

    /**
     * Handle the Offer "deleted" event.
     */
    public function deleted(Offer $offer): void
    {
        event(new OfferDeleted($offer));
    }
}
