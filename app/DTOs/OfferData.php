<?php

namespace App\DTOs;

/**
 * Data Transfer Object for Offer operations.
 * Immutable data structure for transferring offer data between layers.
 */
readonly class OfferData
{
    public function __construct(
        public string $name,
        public string $slug,
        public ?string $image,
        public ?string $description,
        public string $state,
    ) {}

    /**
     * Create from array data.
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            slug: $data['slug'],
            image: $data['image'] ?? null,
            description: $data['description'] ?? null,
            state: $data['state'],
        );
    }

    /**
     * Convert to array for database operations.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => $this->image,
            'description' => $this->description,
            'state' => $this->state,
        ];
    }
}
