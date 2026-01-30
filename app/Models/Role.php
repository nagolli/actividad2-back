<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;

    protected $table = 'roles';
    protected $fillable = ['name'];

    public function employeeUsers()
    {
        return $this->belongsToMany(EmployeeUsers::class, 'employeeUserRoles', 'roleId', 'employeeUserId')
            ->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'rolesPermissions', 'roleId', 'permissionId')
            ->withPivot('permissionLevel')
            ->withTimestamps();
    }
}
