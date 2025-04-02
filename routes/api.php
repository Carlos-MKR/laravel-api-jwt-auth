<?php

use Illuminate\Http\Request;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserAuth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

// Rutas Publicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas con autenticacion
Route::middleware([IsUserAuth::class])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout');
        Route::get('/profile', 'getUser');
    });

    Route::get('/products', [ProductController::class,'getProducts']);

    Route::middleware([IsAdmin::class])->group(function () {
        Route::controller(ProductController::class)->group(function () {
            Route::post('/products', 'addProduct');
            Route::get('/products/{id}', 'getProductById');
            Route::patch('/products/{id}', 'updateProductById');
            Route::delete('/products/{id}', 'deleteProductById');
        });
    });
});