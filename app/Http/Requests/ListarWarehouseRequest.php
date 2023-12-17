<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListarWarehouseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'limit' => 'nullable|integer',
            'id' => 'nullable|integer|exists:warehouses,id',
            'nombre_bodega' => 'nullable|string|max:255',
            'direccion_bodega' => 'nullable|string|max:255',
        ];
    }
}
