<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriaResource;
use App\Models\Categoria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * GET /api/v1/categorias
     * Lista todas las categorías, con el conteo de productos de cada una.
     */
    public function index(): JsonResponse
    {
        $categorias = Categoria::withCount('productos')->latest()->get();

        return response()->json([
            'data' => CategoriaResource::collection($categorias),
        ]);
    }

    /**
     * POST /api/v1/categorias
     * Crea una categoría nueva.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:categorias,nombre'],
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.unique' => 'Ya existe una categoría con ese nombre.',
        ]);

        $categoria = Categoria::create($validated);

        return response()->json([
            'message' => 'Categoría creada correctamente.',
            'data' => new CategoriaResource($categoria),
        ], 201);
    }

    /**
     * GET /api/v1/categorias/{categoria}
     * Muestra el detalle de una categoría puntual.
     */
    public function show(Categoria $categoria): JsonResponse
    {
        $categoria->loadCount('productos');

        return response()->json([
            'data' => new CategoriaResource($categoria),
        ]);
    }

    /**
     * PUT/PATCH /api/v1/categorias/{categoria}
     * Actualiza una categoría existente.
     */
    public function update(Request $request, Categoria $categoria): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:categorias,nombre,' . $categoria->id],
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.unique' => 'Ya existe una categoría con ese nombre.',
        ]);

        $categoria->update($validated);

        return response()->json([
            'message' => 'Categoría actualizada correctamente.',
            'data' => new CategoriaResource($categoria),
        ]);
    }

    /**
     * DELETE /api/v1/categorias/{categoria}
     * Elimina una categoría (en cascada, elimina también sus productos).
     */
    public function destroy(Categoria $categoria): JsonResponse
    {
        $categoria->delete();

        return response()->json([
            'message' => 'Categoría eliminada correctamente.',
        ], 200);
    }
}
