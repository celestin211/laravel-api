<?php

namespace App\Domain\Specifications\Product;

use App\Domain\Specifications\AbstractSpecification;
use App\Models\Product;

/**
 * Specification: Check if a product has a valid price.
 * A product has a valid price if it's greater than zero.
 *
 * @extends AbstractSpecification<Product>
 */
class ProductHasValidPriceSpecification extends AbstractSpecification
{
    /**
     * Check if product has valid price.
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        // Type check needed for runtime safety even though generic type is Product
        if (! $candidate instanceof Product) {
            return false;
        }

        $price = (float) $candidate->price;

        return $price > 0;
    }
}
