<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register alias middleware
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Add page tracking middleware to web group
        $middleware->web(append: [
            \App\Http\Middleware\TrackPageVisits::class,
        ]);

        // HAPUS baris berikut karena Anda tidak pakai Inertia:
        // $middleware->web(append: [
        //     \App\Http\Middleware\HandleInertiaRequests::class,
        //     \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Custom exception handling
        $exceptions->render(function (Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->guest(route('login'));
        });
        
        $exceptions->render(function (Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() == 403) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Unauthorized access.'], 403);
                }
                return response()->view('errors.403', [], 403);
            }
        });
    })->create();
