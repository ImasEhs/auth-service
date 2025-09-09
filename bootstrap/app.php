<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth.apikey' => \App\Http\Middleware\VerifyApiKey::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handler ini secara spesifik menangkap kegagalan otentikasi
        // dan mencegah Laravel melakukan redirect ke route 'login' untuk rute API.
        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            // Hanya berlaku untuk request yang masuk ke /api/*
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'invalid',
                    'message' => 'Token tidak valid atau telah kedaluwarsa.'
                ], 401);
            }
        });

    })->create();
