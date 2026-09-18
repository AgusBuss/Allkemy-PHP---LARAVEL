<?php

namespace App\Http\Requests;

use App\Rules\PrecioValido;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Todavía no hay autenticación (eso llega en la Entrega 4 con JWT),
        // así que por ahora cualquiera puede crear/editar productos.
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
            'nombre' => ['required', 'string', 'max:255'],
            'precio' => ['required', new PrecioValido],
            'stock' => ['required', 'integer', 'min:0'],
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
        ];
    }

    /**
     * Mensajes de error personalizados en español.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'precio.required' => 'El precio es obligatorio.',
            'stock.required' => 'El stock es obligatorio.',
            'stock.min' => 'El stock no puede ser negativo.',
            'categoria_id.required' => 'Tenés que seleccionar una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no existe.',
        ];
    }
}
