<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;
use Illuminate\Support\Str;

/**
 * Value Object for Slug.
 * Immutable representation of a URL-friendly slug with validation.
 */
readonly class Slug
{
    /**
     * Create a new Slug value object.
     *
     * @throws InvalidArgumentException If slug is invalid
     */
    public function __construct(
        private string $value
    ) {
        $this->validate();
    }

    /**
     * Create from a string (will be slugified).
     */
    public static function from(string $value): self
    {
        $slug = Str::slug($value);

        return new self($slug);
    }

    /**
     * Create from an existing slug (validates but doesn't transform).
     */
    public static function fromSlug(string $slug): self
    {
        return new self($slug);
    }

    /**
     * Get the slug value.
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
     * Check if slug equals another slug.
     */
    public function equals(Slug $other): bool
    {
        return $this->value === $other->value;
    }

    /**
     * Validate the slug value.
     *
     * @throws InvalidArgumentException If slug is invalid
     */
    private function validate(): void
    {
        if (empty($this->value)) {
            throw new InvalidArgumentException('Slug cannot be empty.');
        }

        if (strlen($this->value) > 255) {
            throw new InvalidArgumentException('Slug cannot exceed 255 characters.');
        }

        // Slug must contain only lowercase letters, numbers, and hyphens
        if (! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $this->value)) {
            throw new InvalidArgumentException('Slug must contain only lowercase letters, numbers, and hyphens.');
        }
    }
}
