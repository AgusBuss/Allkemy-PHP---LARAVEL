<?php 
require_once __DIR__ . '/../Models/Producto.php';
require_once __DIR__ . '/../Models/Categoria.php';
require_once __DIR__ . '/../Models/Carrito.php';

class ProductoController{
    public function listar() : void{
        // El Controlador ya no crea los productos: se los pide al Modelo.
        $productos = Producto::obtenerCatalogoDeEjemplo();

        $carrito = new Carrito();
        $carrito->agregarProducto($productos[0], 2);

        // Le paso los datos a la vista
        require __DIR__ . '/../Views/productos/listar.php';
    }
}