<?php

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
        $middleware->alias([
            'check.ai.limit'      => \App\Http\Middleware\CheckAiMessageLimit::class,
            'check.doc.limit'     => \App\Http\Middleware\CheckDocumentLimit::class,
            'check.family.limit'  => \App\Http\Middleware\CheckFamilyMemberLimit::class,
            'check.csc.queue'     => \App\Http\Middleware\CheckCscDailyQueue::class,
            'check.ai.toolkit'    => \App\Http\Middleware\CheckAiToolkitAccess::class,
            'role'                => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'          => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission'  => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();