<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarritoItemResource extends JsonResource
{
    /**
     * Transforma un item del carrito en la forma que se expone
     * por la API, incluyendo el subtotal ya calculado.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cantidad' => $this->cantidad,
            'producto' => new ProductoResource($this->whenLoaded('producto')),
            'subtotal' => (float) $this->subtotal,
            'creado_en' => $this->created_at?->toIso8601String(),
        ];
    }
}
