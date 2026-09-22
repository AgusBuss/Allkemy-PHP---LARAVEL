<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ResumenCompraData;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Http\Resources\PedidoResource;
use App\Models\CarritoItem;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * POST /api/v1/checkout
     * Procesa la compra utilizando DB::transaction para garantizar atomicidad y control de stock.
     */
    public function __invoke(CheckoutRequest $request): JsonResponse
    {
        $usuario = auth('api')->user();

        if (!$usuario) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $items = CarritoItem::with('producto')
            ->where('usuario_id', $usuario->id)
            ->get();

        if ($items->isEmpty()) {
            return response()->json([
                'message' => 'No se puede procesar el checkout porque el carrito esta vacio.',
            ], 422);
        }

        foreach ($items as $item) {
            if ($item->producto->stock < $item->cantidad) {
                return response()->json([
                    'message' => "Stock insuficiente para el producto: {$item->producto->nombre}.",
                ], 422);
            }
        }

        $resumen = ResumenCompraData::fromCarrito($items);

        $pedido = DB::transaction(function () use ($request, $usuario, $items, $resumen) {
            $nuevoPedido = Pedido::create([
                'usuario_id'    => $usuario->id,
                'direccion'     => $request->validated('direccion'),
                'ciudad'        => $request->validated('ciudad'),
                'codigo_postal' => $request->validated('codigo_postal'),
                'metodo_pago'   => $request->validated('metodo_pago'),
                'subtotal'      => $resumen->subtotal,
                'impuestos'     => $resumen->impuestos,
                'envio'         => $resumen->costoEnvio ?? $resumen->envio ?? 0, // Clave de BD 'envio' y propiedad del DTO '$costoEnvio'
                'total'         => $resumen->total,
                'estado'        => 'completado',
            ]);

            foreach ($items as $item) {
                PedidoItem::create([
                    'pedido_id'       => $nuevoPedido->id,
                    'producto_id'     => $item->producto_id,
                    'nombre_producto' => $item->producto->nombre,
                    'precio_unitario' => $item->producto->precio,
                    'cantidad'        => $item->cantidad,
                    'subtotal'        => $item->cantidad * $item->producto->precio,
                ]);

                Producto::where('id', $item->producto_id)->decrement('stock', $item->cantidad);
            }

            CarritoItem::where('usuario_id', $usuario->id)->delete();

            return $nuevoPedido;
        });

        $pedido->load('items');

        return response()->json([
            'message' => 'Compra procesada y confirmada con exito.',
            'data'    => new PedidoResource($pedido),
        ], 201);
    }
}
