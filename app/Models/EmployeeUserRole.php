<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeUserRole extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeUserRoleFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $table = 'employeeUserRoles';
    protected $fillable = ['employeeUserId', 'roleId'];
    public $timestamps = true;
}
