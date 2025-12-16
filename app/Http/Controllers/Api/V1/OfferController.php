<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ListOffersRequest;
use App\Http\Resources\OfferResource;
use App\Queries\PublishedOfferQuery;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class OfferController extends Controller
{
    /**
     * List published offers with pagination, sorting, and filtering.
     *
     * Returns a paginated list of published offers with their published products.
     * Supports pagination, sorting by name/created_at/updated_at, and filtering.
     */
    #[OA\Get(
        path: '/offers',
        summary: 'List published offers',
        description: 'Returns a paginated list of published offers with their published products. Supports pagination, sorting, and filtering.',
        tags: ['Offers'],
        parameters: [
            new OA\Parameter(
                name: 'page',
                description: 'Page number',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 1, minimum: 1)
            ),
            new OA\Parameter(
                name: 'per_page',
                description: 'Number of items per page (max 100)',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 15, minimum: 1, maximum: 100)
            ),
            new OA\Parameter(
                name: 'sort_by',
                description: 'Field to sort by',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                    enum: ['name', 'created_at', 'updated_at'],
                    default: 'created_at'
                )
            ),
            new OA\Parameter(
                name: 'sort_order',
                description: 'Sort order',
                in: 'query',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                    enum: ['asc', 'desc'],
                    default: 'desc'
                )
            ),
            new OA\Parameter(
                name: 'filter[name]',
                description: 'Filter by offer name (partial match)',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful response',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Offer')
                        ),
                        new OA\Property(
                            property: 'links',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'first', type: 'string', nullable: true),
                                new OA\Property(property: 'last', type: 'string', nullable: true),
                                new OA\Property(property: 'prev', type: 'string', nullable: true),
                                new OA\Property(property: 'next', type: 'string', nullable: true),
                            ]
                        ),
                        new OA\Property(
                            property: 'meta',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'current_page', type: 'integer'),
                                new OA\Property(property: 'from', type: 'integer', nullable: true),
                                new OA\Property(property: 'last_page', type: 'integer'),
                                new OA\Property(property: 'path', type: 'string'),
                                new OA\Property(property: 'per_page', type: 'integer'),
                                new OA\Property(property: 'to', type: 'integer', nullable: true),
                                new OA\Property(property: 'total', type: 'integer'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'The given data was invalid.'
                        ),
                        new OA\Property(
                            property: 'errors',
                            type: 'object'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function index(ListOffersRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $perPage = min((int) ($validated['per_page'] ?? 15), 100);
        $page = max((int) ($validated['page'] ?? 1), 1);

        $query = new PublishedOfferQuery();
        $builder = $query->build();

        // Apply filtering
        if (isset($validated['filter']['name']) && ! empty($validated['filter']['name'])) {
            $builder->where('name', 'like', '%'.$validated['filter']['name'].'%');
        }

        // Apply sorting
        $sortBy = $validated['sort_by'] ?? 'created_at';
        $sortOrder = $validated['sort_order'] ?? 'desc';
        $allowedSortFields = ['name', 'created_at', 'updated_at'];

        if (in_array($sortBy, $allowedSortFields, true)) {
            $builder->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
        } else {
            // Default sorting if invalid sort_by provided
            $builder->orderBy('created_at', 'desc');
        }

        // Products are already loaded via with() in PublishedOfferQuery::build()
        // No need to call load() on paginator as relations are already eager loaded
        $offers = $builder->paginate($perPage, ['*'], 'page', $page);

        return OfferResource::collection($offers)->response();
    }
}
