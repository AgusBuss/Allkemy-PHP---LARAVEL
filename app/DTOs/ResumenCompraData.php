<?php

namespace App\DTOs;

/**
 * DTO de solo lectura que representa el resumen de compra
 * (subtotal, impuestos, envio y total) devuelto por la API.
 */
class ResumenCompraData
{
    public const PORCENTAJE_IMPUESTO = 0.21;

    public const COSTO_ENVIO = 2000.0;

    public const MONTO_ENVIO_GRATIS = 50000.0;

    public function __construct(
        public readonly float $subtotal,
        public readonly float $impuestos,
        public readonly float $envio,
        public readonly float $total,
    ) {}

    /**
     * Construye el resumen a partir de un subtotal ya calculado,
     * aplicando las reglas de impuestos y envio de la tienda.
     */
    public static function desdeSubtotal(float $subtotal): self
    {
        $impuestos = round($subtotal * self::PORCENTAJE_IMPUESTO, 2);
        $envio = $subtotal >= self::MONTO_ENVIO_GRATIS ? 0.0 : self::COSTO_ENVIO;
        $total = round($subtotal + $impuestos + $envio, 2);

        return new self(
            subtotal: $subtotal,
            impuestos: $impuestos,
            envio: $envio,
            total: $total,
        );
    }

    /**
     * Representacion como array para la respuesta JSON.
     */
    public function toArray(): array
    {
        return [
            'subtotal' => $this->subtotal,
            'impuestos' => $this->impuestos,
            'envio' => $this->envio,
            'total' => $this->total,
        ];
    }
}
