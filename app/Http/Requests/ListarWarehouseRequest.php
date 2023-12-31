<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;


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

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'limit.integer' => 'El límite debe ser un número entero',
            'id.integer' => 'El id debe ser un número entero',
            'id.exists' => 'El id debe existir en la tabla warehouses',
            'nombre_bodega.string' => 'El nombre de la bodega debe ser un texto',
            'nombre_bodega.max' => 'El nombre de la bodega debe tener máximo 255 caracteres',
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
