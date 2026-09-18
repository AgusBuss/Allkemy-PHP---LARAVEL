@extends('layouts.app')

@section('content')
    <h1>Nueva categoría</h1>

    <form action="{{ route('categorias.store') }}" method="POST">
        @csrf

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}">

        <button type="submit">Guardar categoría</button>
    </form>
@endsection
