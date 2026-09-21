<?php

namespace App\Rules;

use App\Models\Producto;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class StockDisponible implements ValidationRule
{
    /**
     * @param  int  $productoId  El producto contra el que se valida el stock.
     */
    public function __construct(
        protected int $productoId
    ) {}

    /**
     * Valida que la cantidad pedida no supere el stock disponible
     * del producto en este momento.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $producto = Producto::find($this->productoId);

        if (! $producto) {
            $fail('El producto seleccionado no existe.');

            return;
        }

        if ((int) $value > $producto->stock) {
            $fail("No hay stock suficiente. Stock disponible: {$producto->stock}.");
        }
    }
}
