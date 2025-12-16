<?php

namespace App\Domain\Specifications;

/**
 * Composite specification that combines two specifications with AND logic.
 *
 * @template T
 *
 * @extends AbstractSpecification<T>
 */
class AndSpecification extends AbstractSpecification
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
     * Check if candidate satisfies both specifications.
     */
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $this->left->isSatisfiedBy($candidate)
            && $this->right->isSatisfiedBy($candidate);
    }
}
