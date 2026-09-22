<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ResumenCompraData;
use App\Http\Controllers\Controller;
use App\Http\Requests\CarritoRequest;
use App\Http\Resources\CarritoItemResource;
use App\Models\CarritoItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    /**
     * GET /api/v1/carrito
     * Retorna los ítems del carrito del usuario autenticado.
     */
    public function index(Request $request): JsonResponse
    {
        $usuario = auth('api')->user();

        $items = CarritoItem::with('producto')
            ->where('usuario_id', $usuario->id)
            ->get();

        $subtotal = $items->sum(fn ($item) => $item->cantidad * $item->producto->precio);

        return response()->json([
            'data' => CarritoItemResource::collection($items),
            'meta' => [
                'subtotal' => (float) $subtotal,
                'cantidad_items' => $items->sum('cantidad'),
            ],
        ]);
    }

    /**
     * POST /api/v1/carrito
     * Agrega un producto o incrementa la cantidad en el carrito del usuario.
     */
    public function store(CarritoRequest $request): JsonResponse
    {
        $usuario = auth('api')->user();
        $productoId = $request->integer('producto_id');
        $cantidad = $request->integer('cantidad');

        $item = CarritoItem::where('usuario_id', $usuario->id)
            ->where('producto_id', $productoId)
            ->first();

        if ($item) {
            $item->increment('cantidad', $cantidad);
            $item->refresh();
        } else {
            $item = CarritoItem::create([
                'usuario_id'  => $usuario->id,
                'producto_id' => $productoId,
                'cantidad'    => $cantidad,
            ]);
        }

        $item->load('producto');

        return response()->json([
            'message' => 'Producto agregado al carrito con exito.',
            'data'    => new CarritoItemResource($item),
        ], 201);
    }

    /**
     * PUT /api/v1/carrito/{carritoItem}
     * Actualiza la cantidad de un ítem existente en el carrito.
     */
    public function update(CarritoRequest $request, CarritoItem $carritoItem): JsonResponse
    {
        $usuario = auth('api')->user();

        if ($carritoItem->usuario_id !== $usuario->id) {
            return response()->json(['message' => 'No autorizado para modificar este item.'], 403);
        }

        $carritoItem->update([
            'cantidad' => $request->integer('cantidad'),
        ]);

        $carritoItem->load('producto');

        return response()->json([
            'message' => 'Cantidad actualizada con exito.',
            'data'    => new CarritoItemResource($carritoItem),
        ]);
    }

    /**
     * DELETE /api/v1/carrito/{carritoItem}
     * Elimina un ítem puntual del carrito.
     */
    public function destroy(Request $request, CarritoItem $carritoItem): JsonResponse
    {
        $usuario = auth('api')->user();

        if ($carritoItem->usuario_id !== $usuario->id) {
            return response()->json(['message' => 'No autorizado para eliminar este item.'], 403);
        }

        $carritoItem->delete();

        return response()->json([
            'message' => 'Producto eliminado del carrito.',
        ]);
    }

    /**
     * DELETE /api/v1/carrito/vaciar
     * Vacía completamente el carrito del usuario autenticado.
     */
    public function vaciar(Request $request): JsonResponse
    {
        $usuario = auth('api')->user();

        CarritoItem::where('usuario_id', $usuario->id)->delete();

        return response()->json([
            'message' => 'Carrito vaciado exitosamente.',
        ]);
    }

    /**
     * GET /api/v1/carrito/resumen
     * Muestra los montos calculados usando el DTO ResumenCompraData.
     */
    public function resumen(Request $request): JsonResponse
    {
        $usuario = auth('api')->user();

        $items = CarritoItem::with('producto')
            ->where('usuario_id', $usuario->id)
            ->get();

        if ($items->isEmpty()) {
            return response()->json(['message' => 'El carrito esta vacio.'], 422);
        }

        $resumen = ResumenCompraData::fromCarritoItems($items);

        return response()->json([
            'data' => $resumen->toArray(),
        ]);
    }
}
