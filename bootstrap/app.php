<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use function Termwind\render;
use Illuminate\Http\Request;
use App\Http\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // disable csrf
        $middleware->validateCsrfTokens(except: [
            '*',
        ]);

        // logging http requests.
        $middleware->append(\Spatie\HttpLogger\Middlewares\HttpLogger::class);

        $middleware->statefulApi();

        $middleware->redirectGuestsTo('');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Exception $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(new Response(1, $e->getMessage(), null), 400);
            }
        });
    })->create();

