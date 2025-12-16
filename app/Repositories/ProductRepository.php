<?php

namespace App\Repositories;

use App\Domain\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

/**
 * Adapter implementation of ProductRepositoryInterface.
 * Repository for Product data access.
 * Abstracts database operations for products.
 */
class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Create a new product.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Update a product.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Product $product, array $data): bool
    {
        return $product->update($data);
    }

    /**
     * Delete a product.
     */
    public function delete(Product $product): bool
    {
        return (bool) $product->delete();
    }

    /**
     * Find a product by ID.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Product
    {
        return Product::findOrFail($id);
    }

    /**
     * Get products by offer ID.
     *
     * @return Collection<int, Product>
     */
    public function findByOfferId(int $offerId): Collection
    {
        return Product::where('offer_id', $offerId)
            ->latest()
            ->get();
    }
}
