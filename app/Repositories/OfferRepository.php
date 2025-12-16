<?php

namespace App\Repositories;

use App\Domain\Repositories\OfferRepositoryInterface;
use App\Models\Offer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Adapter implementation of OfferRepositoryInterface.
 * Repository for Offer data access.
 * Abstracts database operations for offers.
 */
class OfferRepository implements OfferRepositoryInterface
{
    /**
     * Create a new offer.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Offer
    {
        return Offer::create($data);
    }

    /**
     * Update an offer.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Offer $offer, array $data): bool
    {
        return $offer->update($data);
    }

    /**
     * Delete an offer.
     */
    public function delete(Offer $offer): bool
    {
        return (bool) $offer->delete();
    }

    /**
     * Find an offer by ID.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Offer
    {
        return Offer::findOrFail($id);
    }

    /**
     * Find an offer by ID with its products.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findWithProducts(int $id): Offer
    {
        return Offer::with('products')->findOrFail($id);
    }

    /**
     * Get all offers.
     *
     * @return Collection<int, Offer>
     */
    public function all(): Collection
    {
        return Offer::all();
    }

    /**
     * Get offers by state.
     *
     * @return Builder<Offer>
     */
    public function byState(string $state): Builder
    {
        return Offer::query()->ofState($state);
    }
}
