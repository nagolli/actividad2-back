<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientUser extends Model
{
    /** @use HasFactory<\Database\Factories\ClientUserFactory> */
    use HasFactory;

    protected $table = 'clientUsers';
    protected $fillable = ['appUserId', 'password', 'level', 'points'];
    protected $hidden = ['password'];

    public function appUser()
    {
        return $this->belongsTo(AppUser::class, 'appUserId');
    }
}
