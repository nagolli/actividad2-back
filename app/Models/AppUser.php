<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppUser extends Model
{
    /** @use HasFactory<\Database\Factories\AppUserFactory> */
    use HasFactory;

    protected $table = 'appUsers';
    protected $fillable = ['email', 'name', 'surname', 'phone'];

    public function clientUser()
    {
        return $this->hasOne(ClientUsers::class, 'appUserId');
    }

    public function employeeUser()
    {
        return $this->hasOne(EmployeeUsers::class, 'appUserId');
    }

    public function addresses()
    {
        return $this->belongsToMany(Addresses::class, 'appUserAddresses', 'appUserId', 'addressId')
            ->withPivot('name', 'created_at', 'updated_at')
            ->withTimestamps();
    }
}
