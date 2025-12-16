<?php

namespace App\Domain\Specifications\Offer;

use App\Domain\Specifications\AbstractSpecification;
use App\Models\Offer;

/**
 * Specification: Check if an offer is published.
 *
 * @extends AbstractSpecification<Offer>
 */
class PublishedOfferSpecification extends AbstractSpecification
{
    /**
     * Check if offer is published.
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        // Type check needed for runtime safety even though generic type is Offer
        if (! $candidate instanceof Offer) {
            return false;
        }

        return $candidate->state === 'published';
    }
}
