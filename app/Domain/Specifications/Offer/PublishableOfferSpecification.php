<?php

namespace App\Domain\Specifications\Offer;

use App\Domain\Specifications\AbstractSpecification;
use App\Models\Offer;

/**
 * Specification: Check if an offer can be published.
 * An offer can be published if:
 * - It has at least one product
 * - It has a name
 * - It has a slug
 *
 * @extends AbstractSpecification<Offer>
 */
class PublishableOfferSpecification extends AbstractSpecification
{
    /**
     * Check if offer can be published.
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        // Type check needed for runtime safety even though generic type is Offer
        if (! $candidate instanceof Offer) {
            return false;
        }

        // Must have at least one product
        if ($candidate->products()->count() === 0) {
            return false;
        }

        // Must have required fields
        if (empty($candidate->name) || empty($candidate->slug)) {
            return false;
        }

        return true;
    }
}
