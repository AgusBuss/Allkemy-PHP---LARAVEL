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
     * Procesa la compra del carrito del usuario, descuenta stock y vacia el carrito.
     */
    public function __invoke(CheckoutRequest $request): JsonResponse
    {
        $usuarioId = $request->integer('usuario_id');

        $itemsCarrito = CarritoItem::with('producto')
            ->where('usuario_id', $usuarioId)
            ->get();

        if ($itemsCarrito->isEmpty()) {
            return response()->json([
                'message' => 'No se puede procesar el checkout porque el carrito esta vacio.',
            ], 422);
        }

        // Validacion previa de stock antes de abrir la transaccion
        foreach ($itemsCarrito as $item) {
            if ($item->cantidad > $item->producto->stock) {
                return response()->json([
                    'message' => "El producto '{$item->producto->nombre}' no tiene stock suficiente para completar la compra. Stock disponible: {$item->producto->stock}.",
                ], 422);
            }
        }

        $subtotal = (float) $itemsCarrito->sum(fn (CarritoItem $item) => $item->subtotal);
        $resumen = ResumenCompraData::desdeSubtotal($subtotal);

        // Transaccion atomica para garantizar integridad de datos
        $pedido = DB::transaction(function () use ($request, $usuarioId, $itemsCarrito, $resumen) {
            $nuevoPedido = Pedido::create([
                'usuario_id' => $usuarioId,
                'direccion' => $request->string('direccion'),
                'ciudad' => $request->string('ciudad'),
                'codigo_postal' => $request->string('codigo_postal'),
                'metodo_pago' => $request->string('metodo_pago'),
                'estado' => 'completado',
                'subtotal' => $resumen->subtotal,
                'impuestos' => $resumen->impuestos,
                'envio' => $resumen->envio,
                'total' => $resumen->total,
            ]);

            foreach ($itemsCarrito as $item) {
                // Registrar item del pedido
                PedidoItem::create([
                    'pedido_id' => $nuevoPedido->id,
                    'producto_id' => $item->producto_id,
                    'cantidad' => $item->cantidad,
                    'precio_unitario' => $item->producto->precio,
                ]);

                // Descontar stock del producto
                Producto::where('id', $item->producto_id)->decrement('stock', $item->cantidad);
            }

            // Vaciar el carrito del usuario
            CarritoItem::where('usuario_id', $usuarioId)->delete();

            return $nuevoPedido;
        });

        $pedido->load('items.producto');

        return response()->json([
            'message' => 'Compra procesada y confirmada con exito.',
            'data' => new PedidoResource($pedido),
        ], 201);
    }
}
