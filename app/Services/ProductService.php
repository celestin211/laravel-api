<?php

namespace App\Services;

use App\DTOs\ProductData;
use App\Domain\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Http\UploadedFile;

/**
 * Service for Product business logic.
 * Handles all business operations related to products.
 */
class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $repository,
        private FileService $fileService
    ) {}

    /**
     * Create a new product.
     *
     * @param  ProductData  $data  The product data
     * @param  UploadedFile|null  $image  The uploaded image file
     * @return Product The created product
     */
    public function create(ProductData $data, ?UploadedFile $image = null): Product
    {
        $productData = $data->toArray();

        // Handle image upload
        if ($image !== null) {
            $productData['image'] = $this->fileService->storeImage($image, 'products');
        }

        return $this->repository->create($productData);
    }

    /**
     * Update an existing product.
     *
     * @param  int  $id  The product ID
     * @param  ProductData  $data  The updated product data
     * @param  UploadedFile|null  $image  The new image file (optional)
     * @return Product The updated product
     */
    public function update(int $id, ProductData $data, ?UploadedFile $image = null): Product
    {
        $product = $this->repository->findOrFail($id);
        $productData = $data->toArray();

        // Handle image update
        if ($image !== null) {
            $productData['image'] = $this->fileService->updateImage(
                $image,
                $product->image,
                'products'
            );
        } else {
            // Keep existing image if no new one provided
            $productData['image'] = $product->image;
        }

        $this->repository->update($product, $productData);

        $freshProduct = $product->fresh();
        if ($freshProduct === null) {
            throw new \RuntimeException('Failed to refresh product after update.');
        }

        return $freshProduct;
    }

    /**
     * Delete a product and its associated image.
     *
     * @param  int  $id  The product ID
     * @return bool True if deleted successfully
     */
    public function delete(int $id): bool
    {
        $product = $this->repository->findOrFail($id);

        // Delete associated image
        $this->fileService->deleteFile($product->image);

        return $this->repository->delete($product);
    }
}
