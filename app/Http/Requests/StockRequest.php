<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;


class StockRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            'cantidad' => 'required|integer|min:1',
            'tipo' => 'required|in:entrada,salida',
            'observaciones' => 'nullable|string|max:500'
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
            'product_id.required' => 'El campo product_id es requerido',
            'product_id.exists' => 'El campo product_id no existe',
            'cantidad.required' => 'El campo cantidad es requerido',
            'cantidad.integer' => 'El campo cantidad debe ser un entero',
            'cantidad.min' => 'El campo cantidad debe ser mayor a 0',
            'tipo.required' => 'El campo tipo es requerido',
            'tipo.in' => 'El campo tipo debe ser entrada o salida',
            'observaciones.string' => 'El campo observaciones debe ser una cadena de texto',
            'observaciones.max' => 'El campo observaciones debe tener máximo 500 caracteres'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, mixed>
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'msg' => 'Error de validación',
            'errors' => $validator->errors()
        ], Response::HTTP_BAD_REQUEST));
    }
}
