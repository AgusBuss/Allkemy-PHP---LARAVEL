<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoRequest;
use App\Models\Categoria;
use App\Models\Producto;

class ProductoController extends Controller
{
    /**
     * Muestra el listado de productos, con su categoría ya cargada
     * (evita hacer una consulta extra por cada producto: N+1 problem).
     */
    public function index()
    {
        $productos = Producto::with('categoria')->latest()->get();

        return view('productos.index', compact('productos'));
    }

    /**
     * Muestra el formulario para crear un producto nuevo.
     */
    public function create()
    {
        $categorias = Categoria::all();

        return view('productos.create', compact('categorias'));
    }

    /**
     * Guarda el producto nuevo en la base de datos.
     * $request ya llega validado: si algo falla, Laravel redirige
     * solo de vuelta al formulario con los errores.
     */
    public function store(ProductoRequest $request)
    {
        Producto::create($request->validated());

        return redirect()
            ->route('productos.index')
            ->with('status', 'Producto creado correctamente.');
    }

    /**
     * Muestra el detalle de un producto.
     */
    public function show(Producto $producto)
    {
        $producto->load('categoria');

        return view('productos.show', compact('producto'));
    }

    /**
     * Muestra el formulario para editar un producto existente.
     */
    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();

        return view('productos.edit', compact('producto', 'categorias'));
    }

    /**
     * Actualiza un producto existente.
     */
    public function update(ProductoRequest $request, Producto $producto)
    {
        $producto->update($request->validated());

        return redirect()
            ->route('productos.index')
            ->with('status', 'Producto actualizado correctamente.');
    }

    /**
     * Elimina un producto.
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()
            ->route('productos.index')
            ->with('status', 'Producto eliminado correctamente.');
    }
}
