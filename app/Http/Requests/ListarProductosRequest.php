<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class ListarProductosRequest extends FormRequest
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
            'id' => 'nullable|integer|exists:products,id', 
            'nombre' => 'string|nullable',
            'precio_unitario' => 'numeric|nullable',
            'limit' => 'nullable|integer',
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
            'nombre.string' => 'El nombre del producto debe ser una cadena de caracteres',
            'precio_unitario.numeric' => 'El precio unitario del producto debe ser un número',
            'limit.integer' => 'El límite debe ser un número entero',
            'id.integer' => 'El id debe ser un número entero',
            'id.exists' => 'El id debe existir en la tabla products',
            
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json($validator->errors()->all(), Response::HTTP_BAD_REQUEST)
        );
    }
}
