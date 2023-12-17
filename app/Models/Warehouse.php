<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouses';
    protected $fillable = ['nombre_bodega', 'descripcion_bodega', 'direccion_bodega'];

    public function product()
    {
        return $this->hasMany(Product::class);
    }

}
