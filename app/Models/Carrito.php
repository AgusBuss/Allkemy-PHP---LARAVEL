<?php
require_once __DIR__ . "/Producto.php";

class Carrito {
    public array $items = [];

    public function agregarProducto (Producto $producto, int $cantidad): void{
        $this->items[] = [
            "producto" => $producto,
            "cantidad" => $cantidad,
        ];
    }
    public function calcularSubtotal() : float{
        $subtotal = 0;
        foreach ($this ->items as $item){
            $subtotal += $item ['producto']->precio * $item['cantidad'];
        }
        return $subtotal;
    }
}