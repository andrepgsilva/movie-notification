<?php

use Illuminate\Http\Request;
use App\Utils\ResponseErrorHelper;
use Illuminate\Support\Facades\Log;
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
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Throwable $e, Request $request) {
            $context = [
                'message' => $e->getMessage(),
                'payload' => $request->all(),
            ];

            Log::error(
                'Error while requesting: ' . $request->fullUrl(),
                $context
            );
            
            return ResponseErrorHelper::jsonResponse(
                false,
                $e->httpCode ?? 500,
                $e->internalCode ?? null,
                $e->getMessage()
            );
        });
    })->create();
