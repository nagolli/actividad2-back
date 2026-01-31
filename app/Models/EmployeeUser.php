<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeUser extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeUserFactory> */
    use HasFactory;

    protected $table = 'employeeUsers';
    protected $fillable = ['appUserId', 'password', 'isInactive'];
    protected $hidden = ['password'];
    protected $casts = ['isInactive' => 'boolean'];

    public function appUser()
    {
        return $this->belongsTo(AppUser::class, 'appUserId');
    }

    public function role()
    {
        return $this->belongsToMany(Role::class, 'employeeUserRoles', 'employeeUserId', 'roleId')
            ->withTimestamps();
    }
}
