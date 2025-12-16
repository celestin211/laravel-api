<?php

namespace Tests\Unit\Domain\Services;

use App\Domain\Repositories\OfferRepositoryInterface;
use App\Domain\Services\OfferDomainService;
use App\Domain\Specifications\Offer\PublishableOfferSpecification;
use App\Models\Offer;
use App\Models\Product;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class OfferDomainServiceTest extends TestCase
{
    use RefreshDatabase;

    private OfferDomainService $service;

    private OfferRepositoryInterface $repository;

    private PublishableOfferSpecification $specification;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(OfferRepositoryInterface::class);
        $this->specification = new PublishableOfferSpecification();
        $this->service = new OfferDomainService($this->repository, $this->specification);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_can_publish_returns_true_for_publishable_offer(): void
    {
        $offer = Offer::factory()->create([
            'name' => 'Test Offer 1',
            'slug' => 'test-offer-1',
        ]);
        Product::factory()->create(['offer_id' => $offer->id]);

        $result = $this->service->canPublish($offer);

        $this->assertTrue($result);
    }

    public function test_can_publish_returns_false_for_non_publishable_offer(): void
    {
        $offer = Offer::factory()->create([
            'name' => 'Test Offer 2',
            'slug' => 'test-offer-2',
        ]);

        $result = $this->service->canPublish($offer);

        $this->assertFalse($result);
    }

    public function test_validate_publishable_does_not_throw_for_publishable_offer(): void
    {
        $offer = Offer::factory()->create([
            'name' => 'Test Offer 3',
            'slug' => 'test-offer-3',
        ]);
        Product::factory()->create(['offer_id' => $offer->id]);

        $this->service->validatePublishable($offer);

        $this->assertTrue(true); // Si on arrive ici, pas d'exception
    }

    public function test_validate_publishable_throws_exception_for_non_publishable_offer(): void
    {
        $offer = Offer::factory()->create([
            'name' => 'Test Offer 4',
            'slug' => 'test-offer-4',
        ]);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Offer cannot be published. It must have at least one product, a name, and a slug.');

        $this->service->validatePublishable($offer);
    }
}
