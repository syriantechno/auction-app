<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withBroadcasting(
        __DIR__.'/../routes/channels.php'
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'api.token' => \App\Http\Middleware\AuthenticateApiToken::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'track.source' => \App\Http\Middleware\TrackLeadSource::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'login',
            'register',
            'auctions/*/bid',
            'api/*',
        ]);

        // Apply lead source tracking to all web routes
        $middleware->web([
            \App\Http\Middleware\TrackLeadSource::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
