<?php

namespace Tests\Unit\Domain\Specifications\Offer;

use App\Domain\Specifications\Offer\PublishableOfferSpecification;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublishableOfferSpecificationTest extends TestCase
{
    use RefreshDatabase;

    private PublishableOfferSpecification $specification;

    protected function setUp(): void
    {
        parent::setUp();
        $this->specification = new PublishableOfferSpecification();
    }

    public function test_is_satisfied_by_returns_true_for_publishable_offer(): void
    {
        $offer = Offer::factory()->create([
            'name' => 'Test Offer 1',
        ]);
        Product::factory()->create(['offer_id' => $offer->id]);

        $this->assertTrue($this->specification->isSatisfiedBy($offer));
    }

    public function test_is_satisfied_by_returns_false_when_offer_has_no_products(): void
    {
        $offer = Offer::factory()->create([
            'name' => 'Test Offer 2',
        ]);

        $this->assertFalse($this->specification->isSatisfiedBy($offer));
    }

    public function test_is_satisfied_by_returns_false_when_offer_has_no_name(): void
    {
        $offer = Offer::factory()->create([
            'name' => '',
        ]);
        Product::factory()->create(['offer_id' => $offer->id]);

        $this->assertFalse($this->specification->isSatisfiedBy($offer));
    }

    public function test_is_satisfied_by_returns_false_when_offer_has_no_slug(): void
    {
        $offer = Offer::factory()->create([
            'name' => 'Test Offer 4',
            'slug' => '',
        ]);
        Product::factory()->create(['offer_id' => $offer->id]);

        $this->assertFalse($this->specification->isSatisfiedBy($offer));
    }

    public function test_is_satisfied_by_returns_false_for_non_offer_instance(): void
    {
        $this->assertFalse($this->specification->isSatisfiedBy('not-an-offer'));
    }
}
