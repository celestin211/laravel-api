<?php

namespace Tests\Unit\Domain\Specifications\Offer;

use App\Domain\Specifications\Offer\PublishedOfferSpecification;
use App\Models\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublishedOfferSpecificationTest extends TestCase
{
    use RefreshDatabase;

    private PublishedOfferSpecification $specification;

    protected function setUp(): void
    {
        parent::setUp();
        $this->specification = new PublishedOfferSpecification();
    }

    public function test_is_satisfied_by_returns_true_for_published_offer(): void
    {
        $offer = Offer::factory()->published()->create();

        $this->assertTrue($this->specification->isSatisfiedBy($offer));
    }

    public function test_is_satisfied_by_returns_false_for_draft_offer(): void
    {
        $offer = Offer::factory()->create(['state' => 'draft']);

        $this->assertFalse($this->specification->isSatisfiedBy($offer));
    }

    public function test_is_satisfied_by_returns_false_for_hidden_offer(): void
    {
        $offer = Offer::factory()->hidden()->create();

        $this->assertFalse($this->specification->isSatisfiedBy($offer));
    }

    public function test_is_satisfied_by_returns_false_for_non_offer_instance(): void
    {
        $this->assertFalse($this->specification->isSatisfiedBy('not-an-offer'));
    }
}
