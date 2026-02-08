<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppUserAddress extends Model
{
    /** @use HasFactory<\Database\Factories\AppUserAddressesFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $table = 'appUserAddresses';
    protected $fillable = ['addressId', 'appUserId', 'name'];
    public $timestamps = true;
}
