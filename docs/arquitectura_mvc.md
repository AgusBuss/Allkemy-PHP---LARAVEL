# Arquitectura MVC - Entrega 1

Esta entrega usa el patron Modelo-Vista-Controlador de forma manual, sin framework.

## Flujo

Usuario -> public/index.php (lee ?action=)
-> app/Controllers/ProductoController.php (pide los datos)
-> app/Models/Producto.php (provee los datos con obtenerCatalogoDeEjemplo())
-> app/Views/productos/listar.php (muestra el HTML)
-> Respuesta al navegador


## Carpetas y su rol

| Carpeta / Archivo | Rol | Que hace |
|---|---|---|
| public/index.php | Controlador de entrada | Lee la accion de la URL y decide que mostrar |
| app/Controllers/ProductoController.php | Controlador | Pide los datos al modelo y se los pasa a la vista |
| app/Models/Producto.php | Modelo | Clase de dominio + metodo que provee el catalogo |
| app/Models/Categoria.php | Modelo | Clase de dominio |
| app/Models/Usuario.php | Modelo | Clase de dominio |
| app/Models/Carrito.php | Modelo | Clase de dominio, calcula el subtotal |
| app/Views/inicio.php | Vista | Pantalla inicial con el link a listar |
| app/Views/productos/listar.php | Vista | Muestra el catalogo y el carrito |

## Como se comunican

1. El usuario entra a index.php y ve inicio.php con un link a `?action=listar`.
2. index.php lee esa accion y llama a ProductoController::listar().
3. El controlador pide los productos a Producto::obtenerCatalogoDeEjemplo().
4. El controlador arma un Carrito de ejemplo con esos productos.
5. El controlador incluye listar.php, que solo recorre los datos y los muestra.