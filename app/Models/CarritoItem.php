<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarritoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'producto_id',
        'cantidad',
    ];

    protected $casts = [
        'cantidad' => 'integer',
    ];

    /**
     * Un ítem del carrito pertenece a un producto.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Un ítem del carrito pertenece a un usuario.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
