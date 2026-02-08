<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'review';

    public $timestamps = false;
    protected $fillable = [
        'productId',
        'email',
        'review',
        'rating',
    ];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';
    public function product()
    {
        return $this->belongsTo(Product::class, 'productId');
    }

    public function user()
    {
        return $this->belongsTo(AppUser::class, 'email', 'email');
    }

}
