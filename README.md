<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


# Tienda de Negocios - Entrega 2

Migración del dominio de la Entrega 1 (PHP puro) a Laravel 11: modelos
Eloquent, migraciones, validación con Form Requests y vistas Blade.

## Requisitos

- PHP 8.2 o superior
- Composer
- Git

## Instalación

git clone https://github.com/AgusBuss/Allkemy-PHP---LARAVEL.git
cd Allkemy-PHP---LARAVEL
composer install
copy .env.example .env   (en Windows) / cp .env.example .env (en Mac/Linux)
php artisan key:generate
php artisan migrate

## Cómo correr el proyecto

php artisan serve

Abrir en el navegador:
- http://localhost:8000/productos
- http://localhost:8000/categorias

## Estructura

tienda-negocios/
├── entrega-1-php-puro/        (código de la Entrega 1, sin tocar)
├── app/
│   ├── Http/
│   │   ├── Controllers/ProductoController.php, CategoriaController.php
│   │   └── Requests/ProductoRequest.php
│   ├── Models/Producto.php, Categoria.php, Usuario.php, CarritoItem.php
│   └── Rules/PrecioValido.php
├── database/migrations/
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── productos/
│   └── categorias/
├── routes/web.php
└── README.md

## Modelos y relaciones

| Modelo | Relación |
|---|---|
| Categoria | hasMany Producto |
| Producto | belongsTo Categoria, hasMany CarritoItem |
| Usuario | hasMany CarritoItem |
| CarritoItem | belongsTo Producto, belongsTo Usuario |

## PHP puro (Entrega 1) vs Laravel (Entrega 2)

| Aspecto | PHP puro (Entrega 1) | Laravel |
|---|---|---|
| Enrutamiento | `if ($_GET['action'] === 'listar')` manual en `index.php` | `Route::resource()` genera 7 rutas nombradas automáticamente |
| Datos | Array en memoria (`Producto::obtenerCatalogoDeEjemplo()`) | Base de datos real vía Eloquent ORM |
| Relaciones | Objeto `Categoria` embebido a mano en `Producto` | `belongsTo()` / `hasMany()`, resueltas por consulta SQL |
| Validación | No existía | Form Requests con reglas estándar + reglas personalizadas |
| Vistas | PHP embebido, HTML repetido en cada archivo | Blade con `@extends`/`@section`, layout compartido |
| Seguridad | Sin protección CSRF | `@csrf` automático en cada formulario |

El framework no cambia el problema que se resuelve (mostrar productos,
guardarlos, relacionarlos con categorías), pero saca de encima todo el
código repetitivo: enrutamiento, acceso a datos, validación y protección
contra ataques comunes ya vienen resueltos por el framework, no hay que
reinventarlos en cada proyecto.

## Flujo de una petición en Laravel

Ejemplo: el usuario entra a `/productos`.

1. **Ruta** (`routes/web.php`): `Route::resource('productos', ...)`
   matchea `GET /productos` contra `ProductoController@index`.
2. **Controlador** (`ProductoController@index`): pide los datos con
   `Producto::with('categoria')->latest()->get()`.
3. **Modelo** (`Producto`, Eloquent): traduce eso a SQL
   (`SELECT * FROM productos ... JOIN categorias ...`), arma los
   objetos PHP y los devuelve al controlador.
4. **Vista** (`productos/index.blade.php`): el controlador le pasa la
   colección de productos con `compact('productos')`, y Blade recorre
   la lista y genera el HTML final.
5. **Respuesta**: Laravel arma la respuesta HTTP y se la manda al
   navegador.

Para crear/editar productos el flujo agrega un paso: antes de llegar al
controlador, Laravel resuelve el `ProductoRequest` inyectado en la firma
del método (`store(ProductoRequest $request)`) y corre las reglas de
`rules()`. Si algo falla, ni siquiera se ejecuta el código del
controlador: Laravel redirige de vuelta al formulario con los errores.

## Alcance de esta entrega

Todavía no hay API REST (eso es la Entrega 3) ni autenticación/JWT (Entrega
4). El foco de esta entrega es el framework, Eloquent y las vistas Blade
tradicionales.
