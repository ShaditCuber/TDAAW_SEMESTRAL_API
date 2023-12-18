<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;


class ListarStockRequest extends FormRequest
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
            'product_id' => 'nullable|exists:products,id',
            'tipo' => 'nullable|in:entrada,salida',
            'limit' => 'nullable|integer',
            'warehouse_id' => 'nullable|integer|exists:warehouses,id',
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
            'tipo.required' => 'El campo tipo es requerido',
            'tipo.in' => 'El campo tipo debe ser entrada o salida',
            'limit.integer' => 'El campo limit debe ser un entero',
            'warehouse_id.integer' => 'El campo warehouse_id debe ser un entero',
            'warehouse_id.exists' => 'El campo warehouse_id no existe',
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
