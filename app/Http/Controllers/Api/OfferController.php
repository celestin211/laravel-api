<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Queries\PublishedOfferQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * List published offers with pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->input('per_page', 15), 100); // Max 100 per page
        $page = max((int) $request->input('page', 1), 1);

        $query = new PublishedOfferQuery();
        $builder = $query->build();

        // Add sorting if requested
        $sortBy = $request->input('sort_by');
        $sortOrder = $request->input('sort_order', 'desc');
        $allowedSortFields = ['name', 'created_at', 'updated_at'];

        if ($sortBy && in_array($sortBy, $allowedSortFields, true)) {
            $builder->reorder()->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
        }

        $offers = $builder->paginate($perPage, ['*'], 'page', $page);

        // Laravel automatically includes pagination metadata when using paginate()
        return OfferResource::collection($offers)->response();
    }
}
