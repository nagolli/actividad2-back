<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'addresses' => $this->addresses->map(fn ($a) => [
                'city' => $a->city,
                'street' => $a->street,
                'number' => $a->number,
                'country' => $a->country,
                'postalCode' => $a->postalCode,
                'province' => $a->province,
                'floor' => $a->floor,
                'door' => $a->door,
                'staircase' => $a->staircase,
            ]),
            'phone' => $this->phone
        ];
    }
}
