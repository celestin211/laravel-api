<?php

namespace App\Queries;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Query Object for fetching published offers with published products.
 * Used by the public API endpoint.
 */
class PublishedOfferQuery
{
    /**
     * Build the query for published offers with published products.
     *
     * @return Builder<Offer>
     */
    public function build(): Builder
    {
        return Offer::query()
            ->where('state', 'published')
            ->with([
                'products' => function ($query) {
                    $query->where('state', 'published');
                },
            ]);
        // Note: Sorting is handled by the controller to allow custom sorting
    }

    /**
     * Execute the query and get results.
     *
     * @return Collection<int, Offer>
     */
    public function get(): Collection
    {
        return $this->build()->get();
    }
}
