<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';
    protected $fillable = ['nombre', 'descripcion', 'precio_unitario', 'warehouse_id', 'imagen'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function countInventory()
    {
        $stockIn = $this->stocks()->where('tipo', 'entrada')->sum('cantidad');
        $stockOut = $this->stocks()->where('tipo', 'salida')->sum('cantidad');

        return $stockIn - $stockOut;
    }
}
