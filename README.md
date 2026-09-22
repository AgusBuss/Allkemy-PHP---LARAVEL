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




# Tienda de Negocios — Proyecto Integrador Laravel

Proyecto integrador individual desarrollado en PHP 8.5 y Laravel 11 (vía Herd Lite).

---

## Entrega 3: Desarrollo con APIs (Carrito de Compras, Resumen y Checkout)

En esta tercera entrega se expandió el proyecto integrador transformándolo en una API RESTful funcional, incorporando la gestión persistente del carrito de compras, el resumen de montos con reglas de negocio y el procesamiento del checkout con control transaccional de stock.

### 1. Arquitectura y Recursos API

Todas las rutas de la API se encuentran versionadas bajo el prefijo `/api/v1/` (`routes/api.php`):

* **Productos y Categorías (`/api/v1/productos`, `/api/v1/categorias`)**:
  * Controllers API (`ProductoController`, `CategoriaController`) y DTOs de salida (`ProductoResource`, `CategoriaResource`).
  * CRUD completo con respuestas JSON estandarizadas.
* **Carrito de Compras (`/api/v1/carrito`)**:
  * Persistencia en base de datos (`carrito_items`) asociada a `usuario_id`.
  * `GET /api/v1/carrito?usuario_id=X`: Muestra los ítems del usuario y el subtotal acumulado.
  * `POST /api/v1/carrito`: Agrega un producto. Si ya existe en el carrito, acumula la cantidad validando stock disponible con la regla `StockDisponible`.
  * `PUT /api/v1/carrito/{id}`: Actualiza la cantidad de un ítem puntual.
  * `DELETE /api/v1/carrito/{id}`: Elimina un ítem específico del carrito.
  * `DELETE /api/v1/carrito/vaciar?usuario_id=X`: Vacía completamente el carrito del usuario.
* **Resumen de Compra (`GET /api/v1/carrito/resumen?usuario_id=X`)**:
  * Utiliza el DTO de solo lectura `ResumenCompraData`.
  * Calcula subtotal, IVA (21%), envío ($2000 fijo, o gratis para subtotales mayores a $50.000) y total final.
* **Checkout (`POST /api/v1/checkout`)**:
  * Valida los datos de envío y método de pago mediante `CheckoutRequest`.
  * Verifica el stock actualizado de cada producto antes de procesar la orden.
  * Ejecuta una **transacción atómica (`DB::transaction`)** que:
    1. Registra el pedido en la tabla `pedidos` y sus ítems en `pedido_items`.
    2. Descuenta el stock de los productos comprados (`Producto::decrement`).
    3. Vacía el carrito del usuario.
  * Retorna la orden confirmada estructurada a través de `PedidoResource`.

---

### 2. Principios REST Implementados

1. **Uso de Recursos y Nombres en Plural**: Los endpoints representan entidades de dominio sustantivas en plural (`/productos`, `/categorias`, `/carrito`).
2. **Métodos y Verbos HTTP Estándar**:
   * `GET`: Consultas de lectura sin efectos secundarios.
   * `POST`: Creación de recursos (agregar al carrito, confirmar checkout).
   * `PUT`: Actualización completa o de atributos específicos.
   * `DELETE`: Remoción de recursos.
3. **Códigos de Estado HTTP Apropiados**:
   * `200 OK`: Consultas y actualizaciones exitosas.
   * `201 Created`: Recursos creados correctamente (producto agregado, checkout confirmado).
   * `404 Not Found`: Recurso no encontrado en la base de datos (vía Implicit Model Binding).
   * `422 Unprocessable Entity`: Errores de validación de formulario o reglas de negocio (ej. carrito vacío o stock insuficiente).
4. **Representación de Datos Uniforme (JSON)**: Salidas formateadas mediante API Resources y DTOs para mantener el desacoplamiento entre los modelos de la base de datos y la interfaz pública.
5. **Manejo Estandarizado de Errores**: Laravel procesa las excepciones de validación devolviendo un formato JSON consistente con mensajes de error descriptivos.

---

### 3. Colección de Postman

Se incluye en la raíz del repositorio el archivo `Postman_Entrega_3.json` con todas las peticiones configuradas y probadas para verificar el funcionamiento de los endpoints.


---

## Entrega 4: Seguridad, Autenticación (Sanctum/JWT) y Protecciones

En esta cuarta entrega se implementó la capa de seguridad y autenticación para la API REST utilizando **Laravel Sanctum** para la emisión y validación de Bearer Tokens, protegiendo las rutas sensibles de la tienda y previniendo ataques comunes.

### 1. Sistema de Autenticación y Tokens

* **Registro (`POST /api/v1/auth/register`)**: Crea nuevos usuarios almacenando las contraseñas hasheadas mediante `bcrypt` y devuelve un token Bearer de acceso.
* **Inicio de Sesión (`POST /api/v1/auth/login`)**: Valida las credenciales y genera un nuevo token de acceso.
* **Perfil (`GET /api/v1/auth/me`)**: Retorna los datos del usuario autenticado actual.
* **Cierre de Sesión (`POST /api/v1/auth/logout`)**: Revoca el token con el que se realizó la solicitud.

---

### 2. Middlewares y Protecciones Aplicadas

* **Protección de Rutas (`auth:sanctum`)**: Las rutas relacionadas con la gestión del carrito (`/api/v1/carrito/*`) y la confirmación de compras (`/api/v1/checkout`) requieren obligatoriamente un token Bearer válido. Las solicitudes anónimas son rechazadas automáticamente con una respuesta `401 Unauthorized`.
* **Identidad Aislada**: El ID de usuario se extrae dinámicamente del contexto de autenticación (`$request->user()->id`), evitando que usuarios maliciosos manipulen carritos o compras de terceros mediante parámetros inyectados en la petición.
* **Mitigación de Vulnerabilidades**:
  * **SQL Injection**: Prevenido al utilizar Eloquent ORM y consultas preparadas con vinculación de parámetros.
  * **XSS (Cross-Site Scripting)**: Control de tipos riguroso mediante `FormRequests` y escape automático en salidas estructuradas JSON.
  * **Hashing Seguro**: Contraseñas procesadas mediante el algoritmo `bcrypt` (`password_hash`).

---

### 3. Colección de Postman

Se incluye en la raíz del repositorio el archivo `Postman_Entrega_4.json` con la colección de peticiones que incluye el flujo completo de autenticación, la prueba de acceso denegado (`401 Unauthorized`) sin token y el acceso autorizado a las rutas protegidas.



## 🔒 Entrega 4: Autenticación Stateless con JWT (Corrección y Ajustes)

En esta etapa se implementó y ajustó el sistema de autenticación stateless mediante tokens **JWT** (*JSON Web Tokens*) utilizando la librería `php-open-source-saver/jwt-auth`.

### Correcciones y Ajustes Aplicados:
* **Implementación de `JWTSubject` en el Modelo `User`**:
  * Se configuró el modelo `User` (`app/Models/User.php`) asociándolo a la tabla `usuarios` e implementando los métodos obligatorios `getJWTIdentifier()` y `getJWTCustomClaims()`.
* **Desacople de Identificación en Endpoints**:
  * Se eliminó la dependencia de recibir `usuario_id` en el cuerpo de las peticiones (`FormRequests`). El usuario autenticado se deduce directamente de forma segura desde el token Bearer (`auth('api')->user()`).
* **Protección de Rutas y Middlewares**:
  * Se aplicó el middleware `auth:api` a las rutas del carrito (`/api/v1/carrito`) y checkout (`/api/v1/checkout`).
  * Las peticiones sin cabecera `Authorization: Bearer <TOKEN>` o con tokens inválidos/expirados devuelven una respuesta estandarizada `401 Unauthenticated`.

---

## 🧪 Entrega 5: Testing, Aseguramiento de Calidad y Cierre de Proyecto

Se implementó una suite completa de pruebas automatizadas unitarias y de integración utilizando **PHPUnit** y los comandos nativos de Laravel, garantizando la solidez de la lógica de negocio y la seguridad de la API antes de su despliegue.

### 1. Configuración del Entorno de Pruebas
* Configuración de `phpunit.xml` para ejecutar la suite sobre una base de datos **SQLite en memoria** (`:memory:`), asegurando pruebas ultra rápidas e independientes que no afectan la base de datos de desarrollo local.

### 2. Generación de Datos de Prueba (Model Factories)
Se construyeron factories específicas para generar registros consistentes respetando la integridad referencial de las migraciones:
* `UserFactory`: Creación de usuarios de prueba asociados a la tabla `usuarios`.
* `CategoriaFactory`: Generación de categorías de productos.
* `ProductoFactory`: Generación de productos con precios y stock dinámicos.
* `CarritoItemFactory`: Creación de ítems de carrito vinculando usuarios y productos.

### 3. Pruebas Unitarias (Unit Tests)
* **`ResumenCompraDataTest`**: Verifica que la clase DTO `ResumenCompraData` realice correctamente los cálculos de subtotales, discriminación del 21% de IVA, costo de envío y cálculo del total final de la orden.

### 4. Pruebas de Integración (Feature Tests)
* **`AuthApiTest`**: 
  * Registro de nuevos usuarios (`POST /api/v1/auth/register`).
  * Inicio de sesión y generación de token JWT (`POST /api/v1/auth/login`).
  * Verificación de rechazo con código `401` ante accesos no autorizados a rutas protegidas.
  * Verificación de acceso permitido con tokens válidos usando `$this->actingAs($user, 'api')`.
* **`CarritoApiTest`**: 
  * Adición de productos al carrito por parte de un usuario autenticado y verificación de su persistencia en la tabla `carrito_items`.
* **`CheckoutApiTest`**:
  * Ejecución atómica de la compra dentro de una transacción de base de datos (`DB::transaction`).
  * Verificación del descuento automático del stock del producto en la tabla `productos`.
  * Confirmación de la eliminación total de los ítems del carrito tras procesar la orden.

### 5. Ejecución de la Suite de Tests
Para ejecutar la suite de pruebas completa, correr el siguiente comando en la terminal:

```bash
php artisan test


### 📸 Reporte y Evidencia de Ejecución de PHPUnit

Se adjunta la evidencia de ejecución exitosa de la suite completa de pruebas unitarias y de integración (`php artisan test`), confirmando los 7 tests aprobados (17 aserciones) en el entorno de pruebas sobre SQLite en memoria:

![Resultado de Pruebas PHPUnit](public/img/tests-output.png)
