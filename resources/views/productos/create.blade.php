@extends('layouts.app')

@section('content')
    <h1>Nuevo producto</h1>

    <form action="{{ route('productos.store') }}" method="POST">
        @csrf

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}">

        <label for="precio">Precio</label>
        <input type="text" id="precio" name="precio" value="{{ old('precio') }}" placeholder="Ej: 15950.50">

        <label for="stock">Stock</label>
        <input type="number" id="stock" name="stock" value="{{ old('stock') }}">

        <label for="categoria_id">Categoría</label>
        <select id="categoria_id" name="categoria_id">
            <option value="">-- Elegí una categoría --</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}" @selected(old('categoria_id') == $categoria->id)>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select>

        <button type="submit">Guardar producto</button>
    </form>
@endsection
