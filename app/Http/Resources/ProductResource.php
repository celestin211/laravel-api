<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * API Resource for Product.
 * Transforms product data for API responses.
 * Provides a stable contract for API consumers.
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'sku' => $this->resource->sku,
            'image' => $this->resource->image ? asset('storage/'.$this->resource->image) : null,
            'price' => (float) $this->resource->price,
            'state' => $this->resource->state,
            'created_at' => $this->resource->created_at?->toISOString(),
            'updated_at' => $this->resource->updated_at?->toISOString(),
        ];
    }
}
