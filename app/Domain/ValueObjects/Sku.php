<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * Value Object for SKU (Stock Keeping Unit).
 * Immutable representation of a SKU with validation.
 */
readonly class Sku
{
    /**
     * Create a new SKU value object.
     *
     * @throws InvalidArgumentException If SKU is invalid
     */
    public function __construct(
        private string $value
    ) {
        $this->validate();
    }

    /**
     * Create from a string.
     */
    public static function from(string $value): self
    {
        return new self(strtoupper(trim($value)));
    }

    /**
     * Get the SKU value.
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * Convert to string.
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Check if SKU equals another SKU.
     */
    public function equals(Sku $other): bool
    {
        return $this->value === $other->value;
    }

    /**
     * Validate the SKU value.
     *
     * @throws InvalidArgumentException If SKU is invalid
     */
    private function validate(): void
    {
        if (empty($this->value)) {
            throw new InvalidArgumentException('SKU cannot be empty.');
        }

        if (strlen($this->value) > 255) {
            throw new InvalidArgumentException('SKU cannot exceed 255 characters.');
        }

        // SKU must contain only uppercase letters, numbers, and hyphens
        if (! preg_match('/^[A-Z0-9-]+$/', $this->value)) {
            throw new InvalidArgumentException('SKU must contain only uppercase letters, numbers, and hyphens.');
        }
    }
}
