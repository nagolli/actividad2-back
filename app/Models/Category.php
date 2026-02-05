<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'categories';
    protected $fillable = ['name'];
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    public function products()
    {
        return $this->hasMany(Product::class, 'categoryId');
    }
}

class Producto {
    public $id;
    public $nombre;
    public $precio;
    public $descripcion;
    public $existencias;
    public $imagen;
    public $deBaja;
    public $fechaAlta;
    public $valoracionTotal; // atributo derivado
}