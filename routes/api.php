<?php

use App\Http\Controllers\Api\CarritoController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\ProductoController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.')->group(function () {
    Route::apiResource('productos', ProductoController::class);
    Route::apiResource('categorias', CategoriaController::class);

    Route::prefix('carrito')->name('carrito.')->group(function () {
        Route::get('/', [CarritoController::class, 'index'])->name('index');
        Route::get('/resumen', [CarritoController::class, 'resumen'])->name('resumen');
        Route::post('/', [CarritoController::class, 'store'])->name('store');
        Route::delete('/vaciar', [CarritoController::class, 'vaciar'])->name('vaciar');
        Route::put('/{carritoItem}', [CarritoController::class, 'update'])->name('update');
        Route::delete('/{carritoItem}', [CarritoController::class, 'destroy'])->name('destroy');
    });

    Route::post('checkout', CheckoutController::class)->name('checkout');
});
