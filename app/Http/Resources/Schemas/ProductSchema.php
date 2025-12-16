<?php

namespace App\Http\Resources\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Product',
    description: 'A product associated with an offer',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Product Name'),
        new OA\Property(property: 'sku', type: 'string', example: 'PROD-001'),
        new OA\Property(property: 'image', type: 'string', nullable: true, example: 'http://example.com/storage/products/image.jpg'),
        new OA\Property(property: 'price', type: 'number', format: 'float', example: 99.99),
        new OA\Property(property: 'state', type: 'string', enum: ['draft', 'published', 'invisible'], example: 'published'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2024-01-01T00:00:00.000000Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2024-01-01T00:00:00.000000Z'),
    ]
)]
class ProductSchema {}
