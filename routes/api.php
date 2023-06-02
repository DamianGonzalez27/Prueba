<?php

use Illuminate\Support\Facades\Route;

Route::get('products', [
    \App\Http\Controllers\Api\ProductsController::class,
    'index'
]);

Route::get('products/{id}', [
    \App\Http\Controllers\Api\ProductsController::class,
    'show'
]);

Route::post('products', [
    \App\Http\Controllers\Api\ProductsController::class,
    'create'
]);

Route::delete('products/{id}', [
    \App\Http\Controllers\Api\ProductsController::class,
    'delete'
]);

