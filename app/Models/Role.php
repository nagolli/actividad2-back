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

    public function employeeUser()
    {
        return $this->belongsToMany(EmployeeUser::class, 'employeeUserRoles', 'roleId', 'employeeUserId')
            ->withTimestamps();
    }

    public function permission()
    {
        return $this->belongsToMany(Permission::class, 'rolesPermissions', 'roleId', 'permissionId')
            ->withPivot('permissionLevel')
            ->withTimestamps();
    }
}
