@extends('layouts.app')

@section('content')
    <h1>Categorías</h1>

    <p><a href="{{ route('categorias.create') }}">Nueva categoría</a></p>

    @if ($categorias->isEmpty())
        <p>Todavía no hay categorías cargadas.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Cantidad de productos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categorias as $categoria)
                    <tr>
                        <td>{{ $categoria->nombre }}</td>
                        <td>{{ $categoria->productos_count }}</td>
                        <td>
                            <a href="{{ route('categorias.edit', $categoria) }}">Editar</a>
                            <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Seguro? Se van a borrar también sus productos.')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
