<?php

namespace App\Domain\Repositories;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Collection;

/**
 * Port (Interface) for Offer Repository.
 * Defines the contract for offer data access operations.
 */
interface OfferRepositoryInterface
{
    /**
     * Create a new offer.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Offer;

    /**
     * Update an offer.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Offer $offer, array $data): bool;

    /**
     * Delete an offer.
     */
    public function delete(Offer $offer): bool;

    /**
     * Find an offer by ID.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Offer;

    /**
     * Find an offer by ID with its products.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findWithProducts(int $id): Offer;

    /**
     * Get all offers.
     *
     * @return Collection<int, Offer>
     */
    public function all(): Collection;

    /**
     * Get offers by state.
     *
     * @return \Illuminate\Database\Eloquent\Builder<Offer>
     */
    public function byState(string $state): \Illuminate\Database\Eloquent\Builder;
}
