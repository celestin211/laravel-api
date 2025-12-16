<?php

namespace App\Domain\Specifications;

/**
 * Composite specification that combines two specifications with OR logic.
 *
 * @template T
 *
 * @extends AbstractSpecification<T>
 */
class OrSpecification extends AbstractSpecification
{
    /**
     * @param  Specification<T>  $left
     * @param  Specification<T>  $right
     */
    public function __construct(
        private Specification $left,
        private Specification $right
    ) {}

    /**
     * Check if candidate satisfies at least one specification.
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $this->left->isSatisfiedBy($candidate)
            || $this->right->isSatisfiedBy($candidate);
    }
}
