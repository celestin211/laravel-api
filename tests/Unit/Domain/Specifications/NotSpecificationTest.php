<?php

namespace Tests\Unit\Domain\Specifications;

use App\Domain\Specifications\NotSpecification;
use App\Domain\Specifications\Offer\PublishedOfferSpecification;
use App\Models\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotSpecificationTest extends TestCase
{
    use RefreshDatabase;
    public function test_is_satisfied_by_returns_true_when_specification_fails(): void
    {
        $offer = Offer::factory()->create(['state' => 'draft']);

        $spec = new PublishedOfferSpecification();
        $notSpec = new NotSpecification($spec);

        $this->assertTrue($notSpec->isSatisfiedBy($offer));
    }

    public function test_is_satisfied_by_returns_false_when_specification_is_satisfied(): void
    {
        $offer = Offer::factory()->published()->create();

        $spec = new PublishedOfferSpecification();
        $notSpec = new NotSpecification($spec);

        $this->assertFalse($notSpec->isSatisfiedBy($offer));
    }
}
