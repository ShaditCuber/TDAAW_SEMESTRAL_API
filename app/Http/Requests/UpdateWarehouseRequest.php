<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;


class UpdateWarehouseRequest extends FormRequest
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
            'id' => 'required|integer|exists:warehouses,id',
            'nombre_bodega' => 'nullable|string|max:255',
            'descripcion_bodega' => 'nullable|string|max:500',
            'direccion_bodega' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'id.required' => 'El id de la bodega es requerido',
            'id.integer' => 'El id de la bodega debe ser un número entero',
            'id.exists' => 'El id de la bodega no existe',
            'nombre_bodega.string' => 'El nombre de la bodega debe ser un texto',
            'nombre_bodega.max' => 'El nombre de la bodega debe tener máximo 255 caracteres',
            'descripcion_bodega.string' => 'La descripción de la bodega debe ser un texto',
            'descripcion_bodega.max' => 'La descripción de la bodega debe tener máximo 500 caracteres',
            'direccion_bodega.string' => 'La dirección de la bodega debe ser un texto',
            'direccion_bodega.max' => 'La dirección de la bodega debe tener máximo 255 caracteres',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'msg' => 'Error de validación',
            'errors' => $validator->errors()
        ], Response::HTTP_BAD_REQUEST));
    }
}
