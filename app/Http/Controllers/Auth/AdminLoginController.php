<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Ke mana harus redirect setelah login berhasil.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard'; // Sesuaikan dengan route dashboard admin Anda

    /**
     * Menampilkan form login admin.
     */
    public function showLoginForm()
    {
        return view('auth.admin-login'); // Pastikan view ini ada
    }

    /**
     * Menggunakan guard 'admin' untuk proses otentikasi.
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Menangani permintaan logout untuk admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect('/admin/login');
    }
}