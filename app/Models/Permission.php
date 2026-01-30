<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $fillable = ['description'];

    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'rolesPermissions', 'permissionId', 'roleId')
            ->withPivot('permissionLevel')
            ->withTimestamps();
    }
}
