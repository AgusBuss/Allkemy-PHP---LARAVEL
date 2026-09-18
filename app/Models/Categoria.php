<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    /**
     * Atributos que se pueden asignar de forma masiva (create/update).
     */
    protected $fillable = [
        'nombre',
    ];

    /**
     * Una categoría tiene muchos productos.
     */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }
}
