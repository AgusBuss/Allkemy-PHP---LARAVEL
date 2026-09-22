<?php

namespace App\DTOs;

use Illuminate\Support\Collection;

class ResumenCompraData
{
    public float $subtotal;
    public float $impuestos;
    public float $costoEnvio;
    public float $total;

    public function __construct(float $subtotal, float $impuestos, float $costoEnvio, float $total)
    {
        $this->subtotal = $subtotal;
        $this->impuestos = $impuestos;
        $this->costoEnvio = $costoEnvio;
        $this->total = $total;
    }

    /**
     * Genera el DTO calculando los montos desde la colección de ítems del carrito.
     */
    public static function fromCarrito(Collection $items): self
    {
        $subtotal = $items->sum(fn ($item) => $item->cantidad * $item->producto->precio);
        $impuestos = $subtotal * 0.21; // 21% de IVA
        $costoEnvio = $subtotal > 0 ? 500.00 : 0.00; // Costo fijo de envío
        $total = $subtotal + $impuestos + $costoEnvio;

        return new self($subtotal, $impuestos, $costoEnvio, $total);
    }

    public function toArray(): array
    {
        return [
            'subtotal'    => (float) $this->subtotal,
            'impuestos'   => (float) $this->impuestos,
            'costo_envio' => (float) $this->costoEnvio,
            'total'       => (float) $this->total,
        ];
    }
}
