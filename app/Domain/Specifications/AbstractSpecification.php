<?php

namespace App\Domain\Specifications;

/**
 * Abstract base class for specifications.
 * Provides default implementations for combining specifications.
 *
 * @template T
 *
 * @implements Specification<T>
 */
abstract class AbstractSpecification implements Specification
{
    /**
     * Check if the candidate satisfies the specification.
     *
     * @param  T  $candidate  The candidate to check
     * @return bool True if candidate satisfies the specification
     */
    abstract public function isSatisfiedBy(mixed $candidate): bool;

    /**
     * Combine this specification with another using AND logic.
     *
     * @param  Specification<T>  $other
     * @return Specification<T>
     */
    public function and(Specification $other): Specification
    {
        return new AndSpecification($this, $other);
    }

    /**
     * Combine this specification with another using OR logic.
     *
     * @param  Specification<T>  $other
     * @return Specification<T>
     */
    public function or(Specification $other): Specification
    {
        return new OrSpecification($this, $other);
    }

    /**
     * Negate this specification.
     *
     * @return Specification<T>
     */
    public function not(): Specification
    {
        return new NotSpecification($this);
    }
}
