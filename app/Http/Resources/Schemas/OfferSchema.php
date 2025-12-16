<?php

namespace App\Http\Resources\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Offer',
    description: 'An offer with its published products',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Summer Sale'),
        new OA\Property(property: 'slug', type: 'string', example: 'summer-sale'),
        new OA\Property(property: 'image', type: 'string', nullable: true, example: 'http://example.com/storage/offers/image.jpg'),
        new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Amazing summer sale'),
        new OA\Property(property: 'state', type: 'string', enum: ['draft', 'published', 'hidden'], example: 'published'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2024-01-01T00:00:00.000000Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2024-01-01T00:00:00.000000Z'),
        new OA\Property(
            property: 'products',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/Product')
        ),
    ]
)]
class OfferSchema {}
