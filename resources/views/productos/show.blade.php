@extends('layouts.app')

@section('content')
    <h1>{{ $producto->nombre }}</h1>

    <table>
        <tr><th>Precio</th><td>${{ number_format($producto->precio, 2) }}</td></tr>
        <tr><th>Stock</th><td>{{ $producto->stock }}</td></tr>
        <tr><th>Categoría</th><td>{{ $producto->categoria->nombre }}</td></tr>
    </table>

    <p>
        <a href="{{ route('productos.edit', $producto) }}">Editar</a>
        &nbsp;|&nbsp;
        <a href="{{ route('productos.index') }}">Volver al catálogo</a>
    </p>
@endsection
