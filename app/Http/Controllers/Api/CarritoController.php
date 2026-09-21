<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ResumenCompraData;
use App\Http\Controllers\Controller;
use App\Http\Requests\CarritoRequest;
use App\Http\Resources\CarritoItemResource;
use App\Models\CarritoItem;
use App\Models\Producto;
use App\Rules\StockDisponible;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    /**
     * GET /api/v1/carrito?usuario_id=X
     * Muestra el carrito completo de un usuario: sus items y el total.
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'usuario_id' => ['required', 'integer', 'exists:usuarios,id'],
        ]);

        $items = CarritoItem::with('producto.categoria')
            ->where('usuario_id', $request->integer('usuario_id'))
            ->get();

        return response()->json([
            'data' => CarritoItemResource::collection($items),
            'total' => (float) $items->sum(fn (CarritoItem $item) => $item->subtotal),
        ]);
    }

    /**
     * GET /api/v1/carrito/resumen?usuario_id=X
     * Devuelve el resumen de compra del carrito de un usuario:
     * subtotal, impuestos, envio y total.
     */
    public function resumen(Request $request): JsonResponse
    {
        $request->validate([
            'usuario_id' => ['required', 'integer', 'exists:usuarios,id'],
        ]);

        $items = CarritoItem::with('producto')
            ->where('usuario_id', $request->integer('usuario_id'))
            ->get();

        if ($items->isEmpty()) {
            return response()->json([
                'message' => 'El carrito esta vacio.',
            ], 422);
        }

        $subtotal = (float) $items->sum(fn (CarritoItem $item) => $item->subtotal);
        $resumen = ResumenCompraData::desdeSubtotal($subtotal);

        return response()->json([
            'data' => $resumen->toArray(),
        ]);
    }

    /**
     * POST /api/v1/carrito
     * Agrega un producto al carrito. Si el usuario ya tiene ese
     * producto en su carrito, suma la cantidad en vez de duplicar la fila.
     */
    public function store(CarritoRequest $request): JsonResponse
    {
        $usuarioId = $request->integer('usuario_id');
        $productoId = $request->integer('producto_id');
        $cantidadNueva = $request->integer('cantidad');

        $item = CarritoItem::where('usuario_id', $usuarioId)
            ->where('producto_id', $productoId)
            ->first();

        if ($item) {
            $cantidadTotal = $item->cantidad + $cantidadNueva;
            $producto = Producto::find($productoId);

            if ($cantidadTotal > $producto->stock) {
                return response()->json([
                    'message' => "No hay stock suficiente. Stock disponible: {$producto->stock}. Ya tenes {$item->cantidad} en el carrito.",
                ], 422);
            }

            $item->update(['cantidad' => $cantidadTotal]);
        } else {
            $item = CarritoItem::create([
                'usuario_id' => $usuarioId,
                'producto_id' => $productoId,
                'cantidad' => $cantidadNueva,
            ]);
        }

        $item->load('producto.categoria');

        return response()->json([
            'message' => 'Producto agregado al carrito.',
            'data' => new CarritoItemResource($item),
        ], 201);
    }

    /**
     * PUT /api/v1/carrito/{carritoItem}
     * Actualiza la cantidad de un item puntual del carrito.
     */
    public function update(Request $request, CarritoItem $carritoItem): JsonResponse
    {
        $request->validate([
            'cantidad' => [
                'required',
                'integer',
                'min:1',
                new StockDisponible($carritoItem->producto_id),
            ],
        ], [
            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.min' => 'La cantidad tiene que ser al menos 1.',
        ]);

        $carritoItem->update(['cantidad' => $request->integer('cantidad')]);
        $carritoItem->load('producto.categoria');

        return response()->json([
            'message' => 'Cantidad actualizada.',
            'data' => new CarritoItemResource($carritoItem),
        ]);
    }

    /**
     * DELETE /api/v1/carrito/{carritoItem}
     * Elimina un item puntual del carrito.
     */
    public function destroy(CarritoItem $carritoItem): JsonResponse
    {
        $carritoItem->delete();

        return response()->json([
            'message' => 'Producto eliminado del carrito.',
        ]);
    }

    /**
     * DELETE /api/v1/carrito/vaciar?usuario_id=X
     * Vacia todo el carrito de un usuario.
     */
    public function vaciar(Request $request): JsonResponse
    {
        $request->validate([
            'usuario_id' => ['required', 'integer', 'exists:usuarios,id'],
        ]);

        CarritoItem::where('usuario_id', $request->integer('usuario_id'))->delete();

        return response()->json([
            'message' => 'Carrito vaciado correctamente.',
        ]);
    }
}
