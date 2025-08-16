<?php

declare(strict_types=1);

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Http\Middleware\EnsureTokenIsFromAdmin;
use Illuminate\Auth\Middleware\Authenticate;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        // **Добавили класс аутентификации для guard’а**
        'auth'          => Authenticate::class,

        // Sanctum для SPA/API запросов
        'auth:sanctum'  => EnsureFrontendRequestsAreStateful::class,

        // Middleware для Администраторов
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'admin.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,

        // Наш собственный middleware для проверки роли admin
        'admin.token'   => EnsureTokenIsFromAdmin::class,

        // Базовая аутентификация и сессия
        'admin.basic'   => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'admin.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,

        // Прочие стандартные
        'cache.headers'      => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can'                => \Illuminate\Auth\Middleware\Authorize::class,
        'guest'              => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm'   => \Illuminate\Auth\Middleware\RequirePassword::class,
        'precognitive'       => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed'             => \App\Http\Middleware\ValidateSignature::class,
        'throttle'           => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified'           => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];
}
