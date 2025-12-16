<?php

namespace Tests\Unit\Domain\Specifications\Offer;

use App\Domain\Specifications\Offer\OfferHasProductsSpecification;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfferHasProductsSpecificationTest extends TestCase
{
    use RefreshDatabase;

    private OfferHasProductsSpecification $specification;

    protected function setUp(): void
    {
        parent::setUp();
        $this->specification = new OfferHasProductsSpecification();
    }

    public function test_is_satisfied_by_returns_true_when_offer_has_products(): void
    {
        $offer = Offer::factory()->create();
        Product::factory()->count(2)->create(['offer_id' => $offer->id]);

        $this->assertTrue($this->specification->isSatisfiedBy($offer));
    }

    public function test_is_satisfied_by_returns_false_when_offer_has_no_products(): void
    {
        $offer = Offer::factory()->create();

        $this->assertFalse($this->specification->isSatisfiedBy($offer));
    }

    public function test_is_satisfied_by_returns_false_for_non_offer_instance(): void
    {
        $this->assertFalse($this->specification->isSatisfiedBy('not-an-offer'));
    }
}
