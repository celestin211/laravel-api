<?php

namespace App\Http\Controllers\Api\V1;

use OpenApi\Attributes as OA;

/**
 * API v1 Base Controller
 *
 * This controller contains the main OpenAPI documentation for the API v1.
 */
#[OA\Info(
    version: '1.0.0',
    title: 'HelloCSE API',
    description: 'API for managing offers and products. This API provides endpoints to list published offers with their associated products.',
    contact: new OA\Contact(
        name: 'API Support',
        email: 'api@hellocse.com'
    ),
    license: new OA\License(
        name: 'Proprietary'
    )
)]
#[OA\Server(
    url: 'http://127.0.0.1:8000/api/v1',
    description: 'API v1 Server'
)]
#[OA\Tag(
    name: 'Offers',
    description: 'Endpoints for managing offers'
)]
class ApiController {}
