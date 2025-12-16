<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * Value Object for State.
 * Immutable representation of a state with validation against allowed values.
 */
readonly class State
{
    /**
     * Create a new State value object.
     *
     * @param  string  $value  The state value
     * @param  array<string>  $allowedStates  List of allowed state values
     *
     * @throws InvalidArgumentException If state is invalid
     */
    public function __construct(
        private string $value,
        private array $allowedStates
    ) {
        $this->validate();
    }

    /**
     * Create for Offer state.
     */
    public static function offer(string $value): self
    {
        return new self($value, ['draft', 'published', 'hidden']);
    }

    /**
     * Create for Product state.
     */
    public static function product(string $value): self
    {
        return new self($value, ['draft', 'published', 'invisible']);
    }

    /**
     * Get the state value.
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * Check if state is published.
     */
    public function isPublished(): bool
    {
        return $this->value === 'published';
    }

    /**
     * Check if state is draft.
     */
    public function isDraft(): bool
    {
        return $this->value === 'draft';
    }

    /**
     * Check if state is hidden/invisible.
     */
    public function isHidden(): bool
    {
        return in_array($this->value, ['hidden', 'invisible'], true);
    }

    /**
     * Check if state equals another state.
     */
    public function equals(State $other): bool
    {
        return $this->value === $other->value;
    }

    /**
     * Convert to string.
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Validate the state value.
     *
     * @throws InvalidArgumentException If state is invalid
     */
    private function validate(): void
    {
        if (empty($this->value)) {
            throw new InvalidArgumentException('State cannot be empty.');
        }

        if (! in_array($this->value, $this->allowedStates, true)) {
            throw new InvalidArgumentException(
                sprintf('State "%s" is not allowed. Allowed values: %s', $this->value, implode(', ', $this->allowedStates))
            );
        }
    }
}
