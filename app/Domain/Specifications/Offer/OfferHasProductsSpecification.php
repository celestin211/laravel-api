<?php

namespace App\Domain\Specifications\Offer;

use App\Domain\Specifications\AbstractSpecification;
use App\Models\Offer;

/**
 * Specification: Check if an offer has products.
 *
 * @extends AbstractSpecification<Offer>
 */
class OfferHasProductsSpecification extends AbstractSpecification
{
    /**
     * Check if offer has at least one product.
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        // Type check needed for runtime safety even though generic type is Offer
        if (! $candidate instanceof Offer) {
            return false;
        }

        return $candidate->products()->count() > 0;
    }
}
