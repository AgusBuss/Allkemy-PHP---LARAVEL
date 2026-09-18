<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarritoItem extends Model
{
    /**
     * Atributos que se pueden asignar de forma masiva (create/update).
     */
    protected $fillable = [
        'producto_id',
        'usuario_id',
        'cantidad',
    ];

    /**
     * Cada item del carrito pertenece a un producto.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Cada item del carrito pertenece a un usuario.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    /**
     * Subtotal de este item: precio del producto x cantidad.
     * Equivalente al cálculo que hacía Carrito::calcularSubtotal()
     * en la Entrega 1, pero ahora a nivel de item individual.
     */
    public function getSubtotalAttribute(): float
    {
        return $this->producto->precio * $this->cantidad;
    }
}
