<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class RolesPermission extends Model
{
    /** @use HasFactory<\Database\Factories\RolesPermissionFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $table = 'rolesPermissions';
    protected $fillable = ['roleId', 'permissionId', 'permissionLevel'];
    public $timestamps = true;
}
