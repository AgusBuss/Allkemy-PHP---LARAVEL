<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class PrecioValido implements ValidationRule
{
    /**
     * Regla de negocio propia de la tienda: el precio tiene que ser
     * mayor a cero y tener como máximo 2 decimales (no tiene sentido
     * cobrar centavos de centavos).
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_numeric($value)) {
            $fail('El campo :attribute debe ser un número.');

            return;
        }

        if ((float) $value <= 0) {
            $fail('El campo :attribute debe ser mayor a cero.');

            return;
        }

        // Verifica que tenga máximo 2 decimales (ej: 15950.5 ok, 15950.999 no).
        if (! preg_match('/^\d+(\.\d{1,2})?$/', (string) $value)) {
            $fail('El campo :attribute no puede tener más de 2 decimales.');
        }
    }
}
