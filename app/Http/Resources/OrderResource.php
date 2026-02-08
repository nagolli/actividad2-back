<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'state' => $this->state,

            'user' => $this->user->email,

            'address' => [
                'id' => $this->address->id,
                'city' => $this->address->city,
                'street' => $this->address->street,
            ],

            'products' => $this->products->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'quantity' => $p->pivot->quantity,
                    'price' => $p->pivot->price,
                ];
            }),
        ];
    }
}
