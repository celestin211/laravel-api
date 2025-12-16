<?php

namespace App\Domain\Specifications;

/**
 * Specification that negates another specification.
 *
 * @template T
 *
 * @extends AbstractSpecification<T>
 */
class NotSpecification extends AbstractSpecification
{
    /**
     * @param  Specification<T>  $specification
     */
    public function __construct(
        private Specification $specification
    ) {}

    /**
     * Check if candidate does NOT satisfy the specification.
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return ! $this->specification->isSatisfiedBy($candidate);
    }
}
