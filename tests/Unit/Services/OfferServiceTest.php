<?php

namespace Tests\Unit\Services;

use App\DTOs\OfferData;
use App\Models\Offer;
use App\Repositories\OfferRepository;
use App\Services\FileService;
use App\Services\OfferService;
use Illuminate\Http\UploadedFile;
use Mockery;
use Tests\TestCase;

class OfferServiceTest extends TestCase
{
    private OfferService $service;

    private OfferRepository $repository;

    private FileService $fileService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(OfferRepository::class);
        $this->fileService = Mockery::mock(FileService::class);
        $this->service = new OfferService($this->repository, $this->fileService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_can_create_offer_without_image(): void
    {
        $data = new OfferData(
            name: 'Test Offer',
            slug: 'test-offer',
            image: null,
            description: 'Test description',
            state: 'draft'
        );

        $offer = new Offer(['id' => 1, 'name' => 'Test Offer']);

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->with([
                'name' => 'Test Offer',
                'slug' => 'test-offer',
                'image' => null,
                'description' => 'Test description',
                'state' => 'draft',
            ])
            ->andReturn($offer);

        $result = $this->service->create($data);

        $this->assertInstanceOf(Offer::class, $result);
        $this->assertEquals('Test Offer', $result->name);
    }

    public function test_can_create_offer_with_image(): void
    {
        $data = new OfferData(
            name: 'Test Offer',
            slug: 'test-offer',
            image: null,
            description: 'Test description',
            state: 'draft'
        );

        $file = UploadedFile::fake()->image('test.jpg');
        $offer = new Offer(['id' => 1, 'name' => 'Test Offer', 'image' => 'offers/test.jpg']);

        $this->fileService
            ->shouldReceive('storeImage')
            ->once()
            ->with($file, 'offers')
            ->andReturn('offers/test.jpg');

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($array) {
                return $array['name'] === 'Test Offer' && $array['image'] === 'offers/test.jpg';
            }))
            ->andReturn($offer);

        $result = $this->service->create($data, $file);

        $this->assertInstanceOf(Offer::class, $result);
    }

    public function test_can_update_offer_without_image(): void
    {
        $data = new OfferData(
            name: 'Updated Offer',
            slug: 'updated-offer',
            image: null,
            description: 'Updated description',
            state: 'published'
        );

        $offer = Mockery::mock(Offer::class)->makePartial();
        $offer->id = 1;
        $offer->name = 'Old Offer';
        $offer->image = 'old-image.jpg';
        $offer->exists = true;

        $updatedOffer = new Offer(['id' => 1, 'name' => 'Updated Offer', 'image' => 'old-image.jpg']);

        $this->repository
            ->shouldReceive('findOrFail')
            ->once()
            ->with(1)
            ->andReturn($offer);

        $this->repository
            ->shouldReceive('update')
            ->once()
            ->with($offer, Mockery::on(function ($array) {
                return $array['name'] === 'Updated Offer' && $array['image'] === 'old-image.jpg';
            }))
            ->andReturn(true);

        $offer->shouldReceive('fresh')
            ->once()
            ->andReturn($updatedOffer);

        $result = $this->service->update(1, $data);

        $this->assertInstanceOf(Offer::class, $result);
        $this->assertEquals('Updated Offer', $result->name);
    }

    public function test_can_update_offer_with_image(): void
    {
        $data = new OfferData(
            name: 'Updated Offer',
            slug: 'updated-offer',
            image: null,
            description: 'Updated description',
            state: 'published'
        );

        $file = UploadedFile::fake()->image('new.jpg');
        $offer = Mockery::mock(Offer::class)->makePartial();
        $offer->id = 1;
        $offer->name = 'Old Offer';
        $offer->image = 'old-image.jpg';
        $offer->exists = true;

        $updatedOffer = new Offer(['id' => 1, 'name' => 'Updated Offer', 'image' => 'offers/new.jpg']);

        $this->repository
            ->shouldReceive('findOrFail')
            ->once()
            ->with(1)
            ->andReturn($offer);

        $this->fileService
            ->shouldReceive('updateImage')
            ->once()
            ->with($file, 'old-image.jpg', 'offers')
            ->andReturn('offers/new.jpg');

        $this->repository
            ->shouldReceive('update')
            ->once()
            ->with($offer, Mockery::on(function ($array) {
                return $array['image'] === 'offers/new.jpg';
            }))
            ->andReturn(true);

        $offer->shouldReceive('fresh')
            ->once()
            ->andReturn($updatedOffer);

        $result = $this->service->update(1, $data, $file);

        $this->assertInstanceOf(Offer::class, $result);
        $this->assertEquals('Updated Offer', $result->name);
    }

    public function test_can_delete_offer_and_associated_image(): void
    {
        $offer = new Offer(['id' => 1, 'name' => 'Test Offer', 'image' => 'offers/test.jpg']);
        $offer->exists = true;

        $this->repository
            ->shouldReceive('findOrFail')
            ->once()
            ->with(1)
            ->andReturn($offer);

        $this->fileService
            ->shouldReceive('deleteFile')
            ->once()
            ->with('offers/test.jpg')
            ->andReturn(true);

        $this->repository
            ->shouldReceive('delete')
            ->once()
            ->with($offer)
            ->andReturn(true);

        $result = $this->service->delete(1);

        $this->assertTrue($result);
    }

    public function test_can_find_offer_with_products(): void
    {
        $offer = new Offer(['id' => 1, 'name' => 'Test Offer']);

        $this->repository
            ->shouldReceive('findWithProducts')
            ->once()
            ->with(1)
            ->andReturn($offer);

        $result = $this->service->findWithProducts(1);

        $this->assertInstanceOf(Offer::class, $result);
        $this->assertEquals('Test Offer', $result->name);
    }
}
