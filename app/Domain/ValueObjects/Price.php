<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * Value Object for Price.
 * Immutable representation of a price with validation.
 */
readonly class Price
{
    /**
     * Create a new Price value object.
     *
     * @throws InvalidArgumentException If price is invalid
     */
    public function __construct(
        private float $value
    ) {
        $this->validate();
    }

    /**
     * Create from string or numeric value.
     */
    public static function from(mixed $value): self
    {
        if (is_string($value)) {
            $value = (float) $value;
        }

        if (! is_numeric($value)) {
            throw new InvalidArgumentException('Price must be numeric.');
        }

        return new self((float) $value);
    }

    /**
     * Get the price value.
     */
    public function value(): float
    {
        return $this->value;
    }

    /**
     * Get price formatted as string with 2 decimals.
     */
    public function formatted(): string
    {
        return number_format($this->value, 2, '.', '');
    }

    /**
     * Check if price is zero.
     */
    public function isZero(): bool
    {
        return $this->value === 0.0;
    }

    /**
     * Check if price is positive.
     */
    public function isPositive(): bool
    {
        return $this->value > 0.0;
    }

    /**
     * Check if price is negative.
     */
    public function isNegative(): bool
    {
        return $this->value < 0.0;
    }

    /**
     * Add another price.
     */
    public function add(Price $other): self
    {
        return new self($this->value + $other->value);
    }

    /**
     * Subtract another price.
     */
    public function subtract(Price $other): self
    {
        return new self($this->value - $other->value);
    }

    /**
     * Multiply by a factor.
     */
    public function multiply(float $factor): self
    {
        return new self($this->value * $factor);
    }

    /**
     * Convert to array.
     *
     * @return array{value: float|int, formatted: string}
     */
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'formatted' => $this->formatted(),
        ];
    }

    /**
     * Convert to string.
     */
    public function __toString(): string
    {
        return $this->formatted();
    }

    /**
     * Validate the price value.
     *
     * @throws InvalidArgumentException If price is invalid
     */
    private function validate(): void
    {
        if ($this->value < 0) {
            throw new InvalidArgumentException('Price cannot be negative.');
        }

        if ($this->value > 999999.99) {
            throw new InvalidArgumentException('Price cannot exceed 999,999.99.');
        }
    }
}
