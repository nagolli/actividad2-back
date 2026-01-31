<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeUserListResource extends JsonResource
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
            'phone' => $this->appUser->phone,
            'isInactive' => $this->isInactive,
        ];
    }
}
