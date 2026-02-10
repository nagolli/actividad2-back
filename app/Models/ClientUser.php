<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class ClientUser extends Model
{
    /** @use HasFactory<\Database\Factories\ClientUserFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $table = 'clientUsers';
    protected $fillable = ['appUserId', 'password', 'level', 'points'];
    protected $hidden = ['password'];

    public function appUser()
    {
        return $this->belongsTo(AppUser::class, 'appUserId');
    }

    /**
     * Recalcula el nivel según los puntos
     */
    public function recalculateLevel(): void
    {
        $this->level = intdiv($this->points, 100) + 1;
    }

    /**
     * Suma puntos y actualiza nivel
     */
    public function addPoints(int $points): void
    {
        $this->points += $points;
        $this->recalculateLevel();
        $this->save();
    }

    protected static function booted()
    {
        static::saving(function ($client) {
            if ($client->isDirty('points')) {
                $client->recalculateLevel();
            }
        });
    }

    public static function findUserByEmail(string $email): ?ClientUser
    {
        return self::with('appUser')
            ->whereHas('appUser', fn($q) => $q->where('email', $email))
            ->first();
    }


}

