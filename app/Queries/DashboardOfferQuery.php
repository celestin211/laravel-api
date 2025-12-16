<?php

namespace App\Queries;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Query Object for Dashboard offer filtering and searching.
 * Encapsulates complex query logic for the dashboard.
 */
class DashboardOfferQuery
{
    public function __construct(
        private Request $request
    ) {}

    /**
     * Build the query with filters applied.
     *
     * @return Builder<Offer>
     */
    public function build(): Builder
    {
        /** @var Builder<Offer> $query */
        $query = Offer::query();

        // Filter by state
        if ($this->request->filled('state')) {
            /** @var Builder<Offer> $query */
            /** @phpstan-var \Illuminate\Database\Eloquent\Builder<Offer> $query */
            $query = $query->ofState($this->request->input('state'));
        }

        // Search by name
        if ($this->request->filled('name')) {
            $query->where('name', 'like', '%'.$this->request->input('name').'%');
        }

        // Search by slug
        if ($this->request->filled('slug')) {
            $query->where('slug', 'like', '%'.$this->request->input('slug').'%');
        }

        // Sorting
        $sortBy = $this->request->input('sort_by', 'created_at');
        $sortOrder = $this->request->input('sort_order', 'desc');

        // Validate sort_by field
        $allowedSortFields = ['name', 'slug', 'state', 'created_at', 'updated_at'];
        if (in_array($sortBy, $allowedSortFields, true)) {
            $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
        } else {
            // Default sorting by creation date (newest first)
            $query->latest();
        }

        return $query;
    }

    /**
     * Execute the query and get results.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Offer>
     */
    public function get()
    {
        return $this->build()->get();
    }
}
