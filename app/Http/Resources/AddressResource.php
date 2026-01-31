<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'street' => $this->street ?? null,
            'city' => $this->city ?? null,
            'postalCode' => $this->postalCode ?? null,
            'province' => $this->province ?? null,
            'country' => $this->country ?? null,
            'phone' => $this->phone ?? null,
            'number' => $this->number ?? null,
            'floor' => $this->floor ?? null,
            'door' => $this->door ?? null,
            'staircase' => $this->staircase ?? null,
        ];
    }
}
