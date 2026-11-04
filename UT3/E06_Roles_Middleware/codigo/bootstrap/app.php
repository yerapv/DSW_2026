<?php

use App\Http\Middleware\AsegurarGestor;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Este archivo configura la aplicación Laravel, incluyendo rutas, middleware y excepciones.
return Application::configure(basePath: dirname(__DIR__))->withRouting(web: __DIR__ . '/../routes/web.php', commands: __DIR__ . '/../routes/console.php', health: '/up')->withMiddleware(function (Middleware $m): void {

    // Se añaden los middleware de autenticación y autorización a la aplicación, para el rol gestor.
    $m->alias(['gestor' => AsegurarGestor::class]);
})->withExceptions(function (Exceptions $e): void {})->create();
