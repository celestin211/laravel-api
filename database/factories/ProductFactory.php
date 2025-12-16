<?php

namespace Database\Factories;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'offer_id' => Offer::factory(),
            'name' => fake()->words(3, true),
            'sku' => 'SKU-'.strtoupper(fake()->unique()->bothify('####-???')),
            'image' => null,
            'price' => fake()->randomFloat(2, 10, 1000),
            'state' => 'draft',
        ];
    }

    /**
     * Indicate that the product is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'state' => 'published',
        ]);
    }

    /**
     * Indicate that the product is invisible.
     */
    public function invisible(): static
    {
        return $this->state(fn (array $attributes) => [
            'state' => 'invisible',
        ]);
    }

    /**
     * Indicate that the product has an image.
     */
    public function withImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'image' => 'products/'.fake()->uuid().'.jpg',
        ]);
    }
}
