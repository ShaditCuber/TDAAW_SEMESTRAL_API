<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;


class UpdateProductRequest extends FormRequest
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
            'id' => 'required|integer|exists:products,id',
            'nombre' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'precio_unitario' => 'nullable|numeric',
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
            'id.required' => 'El id del producto es requerido',
            'id.integer' => 'El id del producto debe ser un número',
            'id.exists' => 'El id del producto no existe',
            'nombre.string' => 'El nombre del producto debe ser una cadena de caracteres',
            'nombre.max' => 'El nombre del producto debe tener un máximo de 255 caracteres',
            'descripcion.string' => 'La descripción del producto debe ser una cadena de caracteres',
            'descripcion.max' => 'La descripción del producto debe tener un máximo de 500 caracteres',
            'precio_unitario.numeric' => 'El precio unitario del producto debe ser un número',
        ];
    }

    /**
     * @return void
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'msg' => 'Error de validación',
            'errors' => $validator->errors()
        ], Response::HTTP_BAD_REQUEST));
    }
}
