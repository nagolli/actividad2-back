<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';
    protected $fillable = [
        'date',
        'state',
        'userId',
        'addressId',
    ];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    // Los belongsTo serian como las claves ajenas
    public function user()
    {
        return $this->belongsTo(AppUser::class, 'userId', 'id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'addressId');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'esta_en', 'orderId', 'productId')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
