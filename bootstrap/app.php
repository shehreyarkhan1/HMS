<?php

use App\Http\Middleware\EnsureActive;
use App\Http\Middleware\EnsureRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\Http\Middleware\LogActivity;
use App\Providers\AppServiceProvider;
use App\Models\ActivityLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Custom Middlewares
        $middleware->alias([
            'role' => EnsureRole::class,
            'active' => EnsureActive::class,
             'log.activity' => LogActivity::class,
        ]);

        // Global Web Middleware
        $middleware->appendToGroup('web', [
            EnsureActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Industry Standard: Customizing 403 Forbidden Response
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {

            $message = "You don't have permission to perform this action.";

            // 1. Agar Request API ki taraf se hai (ya AJAX hai)
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $message,
                ], 403);
            }

            // 2. Agar Request Normal Web Browser se hai
            // Hum user ko ek pyara sa custom error page dikhayenge
            return response()->view('errors.403', [
                'friendly_message' => $message,
            ], 403);
        });

    })->create();
