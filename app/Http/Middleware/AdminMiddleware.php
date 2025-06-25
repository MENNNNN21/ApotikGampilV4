<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Periksa apakah user terotentikasi menggunakan guard 'admin'
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }

        // Jika tidak, redirect ke halaman login admin
        return redirect()->route('admin.login');
    }
}