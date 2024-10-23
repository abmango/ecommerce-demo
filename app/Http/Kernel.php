<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Las pilas de middleware globales que se ejecutan en cada solicitud.
     *
     * @var array
     */
    protected $middleware = [
        // Maneja las excepciones de mantenimiento de la aplicación.
        \App\Http\Middleware\CheckForMaintenanceMode::class,

        // Valida el tamaño del cuerpo de las solicitudes.
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,

        // Encripta cookies automáticamente.
        \App\Http\Middleware\EncryptCookies::class,

        // Añade cookies a las respuestas.
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,

        // Inicia la sesión del usuario.
        \Illuminate\Session\Middleware\StartSession::class,

        // Prevenir ataques de cross-site scripting (XSS).
        \App\Http\Middleware\VerifyCsrfToken::class,

        \Fruitcake\Cors\HandleCors::class,
    ];

    /**
     * Middleware de grupos para rutas específicas.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\CheckAdmin::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * Los middlewares de las rutas individuales.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'admin' => \App\Http\Middleware\CheckAdmin::class,
        'auth' => \App\Http\Middleware\Authenticate::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
    ];
}