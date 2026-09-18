<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda de Negocios</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 40px auto; padding: 0 20px; color: #222; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px 12px; text-align: left; }
        th { background: #f4f4f4; }
        .status { background: #d4edda; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .errors { background: #f8d7da; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input, select { width: 100%; padding: 6px; margin-top: 4px; box-sizing: border-box; }
        button, .btn { margin-top: 15px; padding: 8px 14px; cursor: pointer; }
        nav a { margin-right: 15px; }
    </style>
</head>
<body>
    <nav>
        <a href="{{ route('productos.index') }}">Catálogo de productos</a>
        <a href="{{ route('productos.create') }}">Nuevo producto</a>
          <a href="{{ route('categorias.index') }}">Categorías</a>
         <a href="{{ route('categorias.create') }}">Nueva categoría</a>
    </nav>

    <hr>

    @if (session('status'))
        <div class="status">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</body>
</html>
