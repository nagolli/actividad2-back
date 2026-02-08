<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'productId' => $this->productId,
            'email' => $this->email,
            'rating' => $this->rating,
            'review' => $this->review,

            // relaciones (solo si están cargadas con ->with())
            'user' => $this->whenLoaded('user', function () {
                return [
                    'email' => $this->user->email,
                    'name' => $this->user->name ?? null,
                ];
            }),

            'product' => $this->whenLoaded('product', function () {
                return [
                    'id' => $this->product->id,
                    'name' => $this->product->name ?? null,
                    'price' => $this->product->price ?? null,
                ];
            }),
        ];
    }
}
