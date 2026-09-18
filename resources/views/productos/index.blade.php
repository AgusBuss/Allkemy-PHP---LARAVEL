@extends('layouts.app')

@section('content')
    <h1>Catálogo de productos</h1>

    @if ($productos->isEmpty())
        <p>Todavía no hay productos cargados. <a href="{{ route('productos.create') }}">Creá el primero</a>.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                    <tr>
                        <td>{{ $producto->nombre }}</td>
                        <td>${{ number_format($producto->precio, 2) }}</td>
                        <td>{{ $producto->stock }}</td>
                        <td>{{ $producto->categoria->nombre }}</td>
                        <td>
                            <a href="{{ route('productos.show', $producto) }}">Ver</a>
                            <a href="{{ route('productos.edit', $producto) }}">Editar</a>
                            <form action="{{ route('productos.destroy', $producto) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Seguro que querés eliminar este producto?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
