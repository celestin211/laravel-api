<?php

namespace Database\Factories;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offer>
 */
class OfferFactory extends Factory
{
    protected $model = Offer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'slug' => fake()->unique()->slug(),
            'image' => null,
            'description' => fake()->paragraph(),
            'state' => 'draft',
        ];
    }

    /**
     * Indicate that the offer is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'state' => 'published',
        ]);
    }

    /**
     * Indicate that the offer is hidden.
     */
    public function hidden(): static
    {
        return $this->state(fn (array $attributes) => [
            'state' => 'hidden',
        ]);
    }

    /**
     * Indicate that the offer has an image.
     */
    public function withImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'image' => 'offers/'.fake()->uuid().'.jpg',
        ]);
    }
}
