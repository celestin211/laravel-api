<?php

namespace App\Domain\Specifications;

/**
 * Base interface for Specification pattern.
 * Specifications encapsulate business rules that can be combined.
 *
 * @template T
 */
interface Specification
{
    /**
     * Check if the candidate satisfies the specification.
     *
     * @param  T  $candidate  The candidate to check
     * @return bool True if candidate satisfies the specification
     */
    public function isSatisfiedBy(mixed $candidate): bool;

    /**
     * Combine this specification with another using AND logic.
     *
     * @param  Specification<T>  $other
     * @return Specification<T>
     */
    public function and(Specification $other): Specification;

    /**
     * Combine this specification with another using OR logic.
     *
     * @param  Specification<T>  $other
     * @return Specification<T>
     */
    public function or(Specification $other): Specification;

    /**
     * Negate this specification.
     *
     * @return Specification<T>
     */
    public function not(): Specification;
}
