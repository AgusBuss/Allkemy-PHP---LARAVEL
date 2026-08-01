# Tienda de Negocios - Entrega 1

Proyecto individual de e-commerce en 5 entregas. Esta primera entrega
implementa el dominio (Producto, Categoria, Usuario, Carrito) en PHP puro
con POO, y una arquitectura MVC manual, sin framework ni base de datos.

## Requisitos

- PHP 8.1 o superior
- Composer
- Git

## Instalacion

git clone https://github.com/AgusBuss/Allkemy-PHP---LARAVEL.git
cd Allkemy-PHP---LARAVEL


## Como correr el proyecto

php -S localhost:8000 -t public


Abrir en el navegador:
- http://localhost:8000/index.php
- http://localhost:8000/index.php?action=listar

## Estructura

tienda-negocios/
├── public/index.php
├── app/
│ ├── Controllers/ProductoController.php
│ ├── Models/
│ │ ├── Producto.php
│ │ ├── Categoria.php
│ │ ├── Usuario.php
│ │ └── Carrito.php
│ └── Views/
│ ├── inicio.php
│ └── productos/listar.php
├── docs/arquitectura-mvc.md
└── README.md


## Clases del dominio

| Clase | Que hace |
|---|---|
| Producto | Datos del producto, provee el catalogo de ejemplo |
| Categoria | Agrupa productos |
| Usuario | Datos basicos de un usuario |
| Carrito | Guarda items y calcula el subtotal |

## Arquitectura

Ver docs/arquitectura-mvc.md para el diagrama y detalle de como se comunican las capas.

## Alcance de esta entrega

No hay base de datos ni Laravel todavia. Los datos viven en memoria. El
objetivo es razonar la arquitectura MVC antes de usar un framework.