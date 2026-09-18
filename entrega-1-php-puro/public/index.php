<?php 
require_once __DIR__ . '/../app/Controllers/ProductoController.php';

$accion = $_GET['action'] ?? 'inicio';
$controller = new ProductoController();

if ($accion === 'listar') {
    $controller->listar();
} else {
    require __DIR__ . '/../app/Views/inicio.php';
}