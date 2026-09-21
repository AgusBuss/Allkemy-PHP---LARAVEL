<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductoRequest;
use App\Http\Resources\ProductoResource;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;

class ProductoController extends Controller
{
    /**
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductoRequest;
use App\Http\Resources\ProductoResource;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;

class ProductoController extends Controller
{
    /**
     * GET /api/v1/productos
     * Lista todos los productos, con su categoría ya cargada.
     */
    public function index(): JsonResponse
    {
        $productos = Producto::with('categoria')->latest()->get();

        return response()->json([
            'data' => ProductoResource::collection($productos),
        ]);
    }

    /**
     * POST /api/v1/productos
     * Crea un producto nuevo. Reutiliza el mismo ProductoRequest
     * (validaciones + regla personalizada PrecioValido) de la Entrega 2.
     */
    public function store(ProductoRequest $request): JsonResponse
    {
        $producto = Producto::create($request->validated());
        $producto->load('categoria');

        return response()->json([
            'message' => 'Producto creado correctamente.',
            'data' => new ProductoResource($producto),
        ], 201);
    }

    /**
     * GET /api/v1/productos/{producto}
     * Muestra el detalle de un producto puntual.
     */
    public function show(Producto $producto): JsonResponse
    {
        $producto->load('categoria');

        return response()->json([
            'data' => new ProductoResource($producto),
        ]);
    }

    /**
     * PUT/PATCH /api/v1/productos/{producto}
     * Actualiza un producto existente.
     */
    public function update(ProductoRequest $request, Producto $producto): JsonResponse
    {
        $producto->update($request->validated());
        $producto->load('categoria');

        return response()->json([
            'message' => 'Producto actualizado correctamente.',
            'data' => new ProductoResource($producto),
        ]);
    }

    /**
     * DELETE /api/v1/productos/{producto}
     * Elimina un producto.
     */
    public function destroy(Producto $producto): JsonResponse
    {
        $producto->delete();

        return response()->json([
            'message' => 'Producto eliminado correctamente.',
        ], 200);
    }
}
