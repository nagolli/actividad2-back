<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->appUser->id,
            'email' => $this->appUser->email,
            'name' => $this->appUser->name,
            'addresses' => $this->appUser->addresses->map(fn ($a) => [
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
            'phone' => $this->appUser->phone,
            'isInactive' => $this->isInactive,
            'roles' => $this->role->map(fn ($r) => [
                'name' => $r->name,
                'permissions' => $r->permission->map(fn ($p) => [
                    'description' => $p->description,
                    'level' => $p->pivot->permissionLevel,
                ])
            ])
        ];
    }
}
