<?php

namespace App\Domain\Specifications\Product;

use App\Domain\Specifications\AbstractSpecification;
use App\Models\Product;

/**
 * Specification: Check if a product is published.
 *
 * @extends AbstractSpecification<Product>
 */
class PublishedProductSpecification extends AbstractSpecification
{
    /**
     * Check if product is published.
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        // Type check needed for runtime safety even though generic type is Product
        if (! $candidate instanceof Product) {
            return false;
        }

        return $candidate->state === 'published';
    }
}
