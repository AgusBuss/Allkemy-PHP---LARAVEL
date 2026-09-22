<?php

namespace App\Http\Requests;

use App\Rules\StockDisponible;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CarritoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'producto_id' => ['required', 'integer', 'exists:productos,id'],
            'cantidad' => [
                'required',
                'integer',
                'min:1',
                new StockDisponible((int) $this->input('producto_id')),
            ],
        ];
    }

    /**
     * Mensajes de error personalizados en español.
     */
    public function messages(): array
    {
        return [
            'producto_id.required' => 'Falta indicar el producto.',
            'producto_id.exists'   => 'El producto indicado no existe.',
            'cantidad.required'    => 'La cantidad es obligatoria.',
            'cantidad.min'         => 'La cantidad tiene que ser al menos 1.',
        ];
    }
}
