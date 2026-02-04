<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    /** @use HasFactory<\Database\Factories\SupplierFactory> */
    use HasFactory;

    protected $table = 'suppliers';
    protected $fillable = ['name', 'phone', 'email', 'inactive'];
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function products()
    {
        return $this->hasMany(Product::class, 'supplierId');
    }
}


