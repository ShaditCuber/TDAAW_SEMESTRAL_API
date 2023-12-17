<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoBodega extends Model
{
    use HasFactory;

    protected $table = 'movimiento_bodegas';
    protected $fillable = ['product_id', 'bodega_origen', 'bodega_destino', 'cantidad', 'observaciones'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

}
