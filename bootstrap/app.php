<?php

use App\Http\Middleware\EnsureActive;
use App\Http\Middleware\EnsureRole;
use App\Http\Middleware\LogActivity;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // --- AAPKA PURANA MIDDLEWARE CODE (RETAINED) ---
        $middleware->alias([
            'role' => EnsureRole::class,
            'active' => EnsureActive::class,
            'log.activity' => LogActivity::class,
        ]);

        $middleware->appendToGroup('web', [
            EnsureActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // 1. DATABASE ERRORS (Added for HMS Stability)
        $exceptions->render(function (QueryException $e, Request $request) {
            Log::error('Database Error: '.$e->getMessage(), [
                'url' => $request->fullUrl(),
                'user_id' => auth()->id(),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Database operation failed.'], 500);
            }

            return redirect()->back()->with('error', 'Database ka masla hai. IT department ko inform karein.');
        });

        // 2. RECORD NOT FOUND (Added to prevent 404 crashes)
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
            }

            return redirect()->back()->with('error', 'Maafi! Ye record system mein nahi mila.');
        });

        // 3. 403 FORBIDDEN (Aapka original logic + professional message)
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            $message = "You don't have permission to perform this action.";
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => $message], 403);
            }

            return response()->view('errors.403', ['friendly_message' => $message], 403);
        });

        // 4. 404 PAGE NOT FOUND (Standard UI)
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'URL not found.'], 404);
            }

            return response()->view('errors.404', [], 404);
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Session expired. Please login again.'], 401);
            }

            return redirect()->guest(route('login'))->with('error', 'Session expire ho gayi, dobara login karein.');
        });

        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            return redirect()->back()->with('error', 'Form expire ho gaya, dobara try karein.');
        });

        // Catch-all fallback for anything not covered above (production only)
        $exceptions->render(function (Throwable $e, Request $request) {
            if (! app()->environment('production')) {
                return null; // let Laravel show debug page in local/dev
            }
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Something went wrong.'], 500);
            }

            return response()->view('errors.500', [], 500);
        });

        // 5. UNEXPECTED ERRORS LOGGING
        $exceptions->report(function (Throwable $e) {
            Log::error('Global System Error: '.$e->getMessage());
        });

    })->create();
