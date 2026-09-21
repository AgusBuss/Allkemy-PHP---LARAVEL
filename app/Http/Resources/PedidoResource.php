<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'usuario_id' => $this->usuario_id,
            'envio' => [
                'direccion' => $this->direccion,
                'ciudad' => $this->ciudad,
                'codigo_postal' => $this->codigo_postal,
            ],
            'metodo_pago' => $this->metodo_pago,
            'estado' => $this->estado,
            'totales' => [
                'subtotal' => (float) $this->subtotal,
                'impuestos' => (float) $this->impuestos,
                'costo_envio' => (float) $this->envio,
                'total' => (float) $this->total,
            ],
            'items' => PedidoItemResource::collection($this->whenLoaded('items')),
            'creado_el' => $this->created_at->toIso8601String(),
        ];
    }
}
