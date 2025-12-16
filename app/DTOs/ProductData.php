<?php

namespace App\DTOs;

/**
 * Data Transfer Object for Product operations.
 * Immutable data structure for transferring product data between layers.
 */
readonly class ProductData
{
    public function __construct(
        public int $offerId,
        public string $name,
        public string $sku,
        public ?string $image,
        public float $price,
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
            offerId: (int) $data['offer_id'],
            name: $data['name'],
            sku: $data['sku'],
            image: $data['image'] ?? null,
            price: (float) $data['price'],
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
            'offer_id' => $this->offerId,
            'name' => $this->name,
            'sku' => $this->sku,
            'image' => $this->image,
            'price' => $this->price,
            'state' => $this->state,
        ];
    }
}
