<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Usuario extends Model
{
    /**
     * Atributos que se pueden asignar de forma masiva (create/update).
     */
    protected $fillable = [
        'nombre',
        'email',
    ];

    /**
     * Un usuario puede tener muchos items en su carrito.
     */
    public function carritoItems(): HasMany
    {
        return $this->hasMany(CarritoItem::class);
    }
}
