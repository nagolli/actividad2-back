<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RolesPermission extends Model
{
    /** @use HasFactory<\Database\Factories\RolesPermissionFactory> */
    use HasFactory;

    protected $table = 'rolesPermissions';
    protected $fillable = ['roleId', 'permissionId', 'permissionLevel'];
    public $timestamps = true;
}
