<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarritoController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\ProductoController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.')->group(function () {

    // Rutas Públicas de Autenticación
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register'])->name('auth.register');
        Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    });

    // Catálogo Público de Productos y Categorías
    Route::apiResource('productos', ProductoController::class)->only(['index', 'show']);
    Route::apiResource('categorias', CategoriaController::class)->only(['index', 'show']);

    // Rutas Protegidas (Requieren Token Bearer JWT)
    Route::middleware('auth:api')->group(function () {

        // Perfil y Logout
        Route::prefix('auth')->group(function () {
            Route::get('me', [AuthController::class, 'me'])->name('auth.me');
            Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
        });

        // Administración de Productos y Categorías
        Route::apiResource('productos', ProductoController::class)->except(['index', 'show']);
        Route::apiResource('categorias', CategoriaController::class)->except(['index', 'show']);

        // Carrito de Compras del Usuario Autenticado
        Route::prefix('carrito')->name('carrito.')->group(function () {
            Route::get('/', [CarritoController::class, 'index'])->name('index');
            Route::get('/resumen', [CarritoController::class, 'resumen'])->name('resumen');
            Route::post('/', [CarritoController::class, 'store'])->name('store');
            Route::delete('/vaciar', [CarritoController::class, 'vaciar'])->name('vaciar');
            Route::put('/{carritoItem}', [CarritoController::class, 'update'])->name('update');
            Route::delete('/{carritoItem}', [CarritoController::class, 'destroy'])->name('destroy');
        });

        // Procesar Checkout
        Route::post('checkout', CheckoutController::class)->name('checkout');
    });
});
