<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;



class ProductRequest extends FormRequest
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
            'id' => 'exists:products,id|numeric',
            'nombre' => 'required|string',
            'descripcion' => 'string|nullable',
            'precio_unitario' => 'required|numeric',
            'warehouse_id' => 'required|numeric',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg'
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
            'imagen.image' => 'La imagen debe ser un archivo de imagen',
            'imagen.mimes' => 'La imagen debe ser un archivo de tipo: jpeg, png, jpg, gif, svg',
            'imagen.max' => 'La imagen debe tener un tamaño máximo de 2048 kilobytes',
            'id.numeric' => 'El id del producto debe ser un número',
            'id.exists' => 'El id del producto no existe',
            'id.required' => 'El id del producto es requerido',
            'nombre.required' => 'El nombre del producto es requerido',
            'nombre.string' => 'El nombre del producto debe ser una cadena de caracteres',
            'descripcion.string' => 'La descripción del producto debe ser una cadena de caracteres',
            'precio_unitario.required' => 'El precio unitario del producto es requerido',
            'precio_unitario.numeric' => 'El precio unitario del producto debe ser un número',
            'warehouse_id.required' => 'La bodega del producto es requerida',
            'warehouse_id.numeric' => 'La bodega del producto debe ser un número'
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
