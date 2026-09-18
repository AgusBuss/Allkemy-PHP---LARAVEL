@extends('layouts.app')

@section('content')
    <h1>Editar categoría</h1>

    <form action="{{ route('categorias.update', $categoria) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $categoria->nombre) }}">

        <button type="submit">Actualizar categoría</button>
    </form>
@endsection
