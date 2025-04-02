<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserAuth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Registrar middlewares
        IsUserAuth::class; // Middleware para verificar si el usuario esta autenticado
        IsAdmin::class; // Middleware para verificar si el usuario autenticado tiene el rol de administrador
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
