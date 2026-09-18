<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('productos.index');
});

Route::resource('productos', ProductoController::class);
Route::resource('categorias', CategoriaController::class);
