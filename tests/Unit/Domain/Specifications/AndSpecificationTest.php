<?php

namespace Tests\Unit\Domain\Specifications;

use App\Domain\Specifications\AndSpecification;
use App\Domain\Specifications\Offer\PublishedOfferSpecification;
use App\Domain\Specifications\Offer\OfferHasProductsSpecification;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AndSpecificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_satisfied_by_returns_true_when_both_specifications_are_satisfied(): void
    {
        $offer = Offer::factory()->published()->create();
        Product::factory()->create(['offer_id' => $offer->id]);

        $spec1 = new PublishedOfferSpecification();
        $spec2 = new OfferHasProductsSpecification();
        $andSpec = new AndSpecification($spec1, $spec2);

        $this->assertTrue($andSpec->isSatisfiedBy($offer));
    }

    public function test_is_satisfied_by_returns_false_when_first_specification_fails(): void
    {
        $offer = Offer::factory()->create(['state' => 'draft']);
        Product::factory()->create(['offer_id' => $offer->id]);

        $spec1 = new PublishedOfferSpecification();
        $spec2 = new OfferHasProductsSpecification();
        $andSpec = new AndSpecification($spec1, $spec2);

        $this->assertFalse($andSpec->isSatisfiedBy($offer));
    }

    public function test_is_satisfied_by_returns_false_when_second_specification_fails(): void
    {
        $offer = Offer::factory()->published()->create();

        $spec1 = new PublishedOfferSpecification();
        $spec2 = new OfferHasProductsSpecification();
        $andSpec = new AndSpecification($spec1, $spec2);

        $this->assertFalse($andSpec->isSatisfiedBy($offer));
    }

    public function test_is_satisfied_by_returns_false_when_both_specifications_fail(): void
    {
        $offer = Offer::factory()->create(['state' => 'draft']);

        $spec1 = new PublishedOfferSpecification();
        $spec2 = new OfferHasProductsSpecification();
        $andSpec = new AndSpecification($spec1, $spec2);

        $this->assertFalse($andSpec->isSatisfiedBy($offer));
    }
}
