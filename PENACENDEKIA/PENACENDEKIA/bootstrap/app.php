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
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'login',
            'logout',
        ]);
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            if ($request->is('admin*') || $request->is('designer*')) {
                return route('login', ['portal' => 'staff']);
            }
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) {
            if ($e->getStatusCode() === 419) {
                return redirect()->back()
                    ->withInput($request->except('_token', 'password'))
                    ->with('error', 'Sesi form Anda telah diperbarui demi keamanan. Silakan coba klik tombol kembali.');
            }
        });
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            return redirect()->back()
                ->withInput($request->except('_token', 'password'))
                ->with('error', 'Sesi form Anda telah kedaluwarsa demi keamanan. Silakan coba klik tombol kembali.');
        });
    })->create();
