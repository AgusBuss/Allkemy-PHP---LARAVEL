<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Muestra el listado de categorías.
     */
    public function index()
    {
        $categorias = Categoria::withCount('productos')->latest()->get();

        return view('categorias.index', compact('categorias'));
    }

    /**
     * Muestra el formulario para crear una categoría nueva.
     */
    public function create()
    {
        return view('categorias.create');
    }

    /**
     * Guarda la categoría nueva.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:categorias,nombre'],
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.unique' => 'Ya existe una categoría con ese nombre.',
        ]);

        Categoria::create($validated);

        return redirect()
            ->route('categorias.index')
            ->with('status', 'Categoría creada correctamente.');
    }

    /**
     * No se usa: las categorías no tienen vista de detalle propia.
     */
    public function show(Categoria $categoria)
    {
        return redirect()->route('categorias.index');
    }

    /**
     * Muestra el formulario para editar una categoría.
     */
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Actualiza una categoría existente.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:categorias,nombre,' . $categoria->id],
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.unique' => 'Ya existe una categoría con ese nombre.',
        ]);

        $categoria->update($validated);

        return redirect()
            ->route('categorias.index')
            ->with('status', 'Categoría actualizada correctamente.');
    }

    /**
     * Elimina una categoría (y, en cascada, sus productos —
     * definido así en la migración con cascadeOnDelete).
     */
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();

        return redirect()
            ->route('categorias.index')
            ->with('status', 'Categoría eliminada correctamente.');
    }
}
