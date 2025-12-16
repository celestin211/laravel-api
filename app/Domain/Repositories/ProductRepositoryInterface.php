<?php

namespace App\Domain\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

/**
 * Port (Interface) for Product Repository.
 * Defines the contract for product data access operations.
 */
interface ProductRepositoryInterface
{
    /**
     * Create a new product.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Product;

    /**
     * Update a product.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Product $product, array $data): bool;

    /**
     * Delete a product.
     */
    public function delete(Product $product): bool;

    /**
     * Find a product by ID.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Product;

    /**
     * Get products by offer ID.
     *
     * @return Collection<int, Product>
     */
    public function findByOfferId(int $offerId): Collection;
}
