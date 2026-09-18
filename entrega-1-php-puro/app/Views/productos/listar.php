<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda de Negocios</title>
</head>
<body>
    <h1>Catálogo de productos</h1>
    <ul>
        <?php foreach ($productos as $producto): ?>
            <li>
                <?= $producto->nombre ?> -
                $<?= $producto->precio ?> -
                Categoría: <?= $producto->categoria->nombre ?> -
                Stock: <?= $producto->stock ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Carrito de ejemplo</h2>
    <p>Subtotal: $<?= $carrito->calcularSubtotal() ?></p>
</body>
</html>