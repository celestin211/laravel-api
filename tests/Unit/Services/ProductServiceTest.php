<?php

namespace Tests\Unit\Services;

use App\DTOs\ProductData;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\FileService;
use App\Services\ProductService;
use Illuminate\Http\UploadedFile;
use Mockery;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    private ProductService $service;

    private ProductRepository $repository;

    private FileService $fileService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(ProductRepository::class);
        $this->fileService = Mockery::mock(FileService::class);
        $this->service = new ProductService($this->repository, $this->fileService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_can_create_product_without_image(): void
    {
        $data = new ProductData(
            offerId: 1,
            name: 'Test Product',
            sku: 'SKU-001',
            image: null,
            price: 99.99,
            state: 'draft'
        );

        $product = new Product(['id' => 1, 'name' => 'Test Product']);

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->with([
                'offer_id' => 1,
                'name' => 'Test Product',
                'sku' => 'SKU-001',
                'image' => null,
                'price' => 99.99,
                'state' => 'draft',
            ])
            ->andReturn($product);

        $result = $this->service->create($data);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals('Test Product', $result->name);
    }

    public function test_can_create_product_with_image(): void
    {
        $data = new ProductData(
            offerId: 1,
            name: 'Test Product',
            sku: 'SKU-001',
            image: null,
            price: 99.99,
            state: 'draft'
        );

        $file = UploadedFile::fake()->image('product.jpg');
        $product = new Product(['id' => 1, 'name' => 'Test Product', 'image' => 'products/product.jpg']);

        $this->fileService
            ->shouldReceive('storeImage')
            ->once()
            ->with($file, 'products')
            ->andReturn('products/product.jpg');

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($array) {
                return $array['name'] === 'Test Product' && $array['image'] === 'products/product.jpg';
            }))
            ->andReturn($product);

        $result = $this->service->create($data, $file);

        $this->assertInstanceOf(Product::class, $result);
    }

    public function test_can_update_product_without_image(): void
    {
        $data = new ProductData(
            offerId: 1,
            name: 'Updated Product',
            sku: 'SKU-002',
            image: null,
            price: 149.99,
            state: 'published'
        );

        $product = Mockery::mock(Product::class)->makePartial();
        $product->id = 1;
        $product->name = 'Old Product';
        $product->image = 'old-image.jpg';
        $product->exists = true;

        $updatedProduct = new Product(['id' => 1, 'name' => 'Updated Product', 'image' => 'old-image.jpg']);

        $this->repository
            ->shouldReceive('findOrFail')
            ->once()
            ->with(1)
            ->andReturn($product);

        $this->repository
            ->shouldReceive('update')
            ->once()
            ->with($product, Mockery::on(function ($array) {
                return $array['name'] === 'Updated Product' && $array['image'] === 'old-image.jpg';
            }))
            ->andReturn(true);

        $product->shouldReceive('fresh')
            ->once()
            ->andReturn($updatedProduct);

        $result = $this->service->update(1, $data);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals('Updated Product', $result->name);
    }

    public function test_can_update_product_with_image(): void
    {
        $data = new ProductData(
            offerId: 1,
            name: 'Updated Product',
            sku: 'SKU-002',
            image: null,
            price: 149.99,
            state: 'published'
        );

        $file = UploadedFile::fake()->image('new-product.jpg');
        $product = Mockery::mock(Product::class)->makePartial();
        $product->id = 1;
        $product->name = 'Old Product';
        $product->image = 'old-image.jpg';
        $product->exists = true;

        $updatedProduct = new Product(['id' => 1, 'name' => 'Updated Product', 'image' => 'products/new-product.jpg']);

        $this->repository
            ->shouldReceive('findOrFail')
            ->once()
            ->with(1)
            ->andReturn($product);

        $this->fileService
            ->shouldReceive('updateImage')
            ->once()
            ->with($file, 'old-image.jpg', 'products')
            ->andReturn('products/new-product.jpg');

        $this->repository
            ->shouldReceive('update')
            ->once()
            ->with($product, Mockery::on(function ($array) {
                return $array['image'] === 'products/new-product.jpg';
            }))
            ->andReturn(true);

        $product->shouldReceive('fresh')
            ->once()
            ->andReturn($updatedProduct);

        $result = $this->service->update(1, $data, $file);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals('Updated Product', $result->name);
    }

    public function test_can_delete_product_and_associated_image(): void
    {
        $product = new Product(['id' => 1, 'name' => 'Test Product', 'image' => 'products/test.jpg']);
        $product->exists = true;

        $this->repository
            ->shouldReceive('findOrFail')
            ->once()
            ->with(1)
            ->andReturn($product);

        $this->fileService
            ->shouldReceive('deleteFile')
            ->once()
            ->with('products/test.jpg')
            ->andReturn(true);

        $this->repository
            ->shouldReceive('delete')
            ->once()
            ->with($product)
            ->andReturn(true);

        $result = $this->service->delete(1);

        $this->assertTrue($result);
    }
}
