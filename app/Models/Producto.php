<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    /**
     * Atributos que se pueden asignar de forma masiva (create/update).
     */
    protected $fillable = [
        'nombre',
        'precio',
        'stock',
        'categoria_id',
    ];

    /**
     * Casteo de tipos: asegura que 'precio' siempre se maneje como
     * decimal con 2 posiciones y 'stock' como entero.
     */
    protected $casts = [
        'precio' => 'decimal:2',
        'stock' => 'integer',
    ];

    /**
     * Un producto pertenece a una categoría.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Un producto puede estar en muchos items de carrito.
     */
    public function carritoItems(): HasMany
    {
        return $this->hasMany(CarritoItem::class);
    }
}
