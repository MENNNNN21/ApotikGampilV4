<?php
// File: bootstrap/app.php

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
    // DAFTARKAN ALIAS DI SINI
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ]);

    // Konfigurasi redirect yang sudah kita buat sebelumnya biarkan saja
    $middleware->redirectGuestsTo(function ($request) {
        if ($request->is('admin') || $request->is('admin/*')) {
            return route('admin.login');
        }
        return route('login');
    });
})
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
// --- TAMBAHKAN KODE INI ---
