<?php
require_once __DIR__. "/Categoria.php";

class Producto 
{
    public int $id;
    public string $nombre;
    public float $precio;
    public int $stock;
    public Categoria $categoria;


    public function __construct(int $id, string $nombre, float $precio, int $stock, Categoria $categoria){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->categoria = $categoria;
    }

    // El Modelo provee los datos: el Controlador ya no arma el catálogo,
    // solo se lo pide a esta clase.
    public static function obtenerCatalogoDeEjemplo(): array
    {
        $electronica = new Categoria(1, 'Electrónica');
        $hogar = new Categoria(2, 'Hogar');

        return [
            new Producto(1, 'Auriculares', 15950, 25, $electronica),
            new Producto(2, 'Cafetera', 2200, 10, $hogar),
            new Producto(3, 'Mouse', 800, 5, $electronica),
        ];
    }
}