@extends('layouts.app')

@section('content')
    <h1>Editar producto</h1>

    <form action="{{ route('productos.update', $producto) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}">

        <label for="precio">Precio</label>
        <input type="text" id="precio" name="precio" value="{{ old('precio', $producto->precio) }}">

        <label for="stock">Stock</label>
        <input type="number" id="stock" name="stock" value="{{ old('stock', $producto->stock) }}">

        <label for="categoria_id">Categoría</label>
        <select id="categoria_id" name="categoria_id">
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}" @selected(old('categoria_id', $producto->categoria_id) == $categoria->id)>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select>

        <button type="submit">Actualizar producto</button>
    </form>
@endsection
