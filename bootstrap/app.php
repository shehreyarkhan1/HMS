<?php

use App\Http\Middleware\EnsureActive;
use App\Http\Middleware\EnsureRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    // ->withMiddleware(function (Middleware $middleware): void {
    //     //
    // })

   ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role'   => EnsureRole::class,
        'active' => EnsureActive::class,
    ]);

    // Har web request pe active check
    $middleware->appendToGroup('web', [
        \App\Http\Middleware\EnsureActive::class,
    ]);
})
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
