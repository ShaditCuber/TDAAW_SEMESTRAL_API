<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio_unitario' => $this->precio_unitario,
            'warehouse_name' => $this->warehouse->nombre_bodega,
            'inventory_count' => $this->countInventory(), // Include the inventory count,
            'warehouse_id' => $this->warehouse_id,
        ];
    }
}
