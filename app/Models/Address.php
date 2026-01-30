<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;

    protected $table = 'addresses';
    protected $fillable = ['postalCode', 'floor', 'door', 'staircase', 'country', 'province', 'city', 'street', 'number'];

    public function appUsers()
    {
        return $this->belongsToMany(AppUser::class, 'appUserAddresses', 'addressId', 'appUserId')
            ->withPivot('name', 'created_at', 'updated_at')
            ->withTimestamps();
    }
}
