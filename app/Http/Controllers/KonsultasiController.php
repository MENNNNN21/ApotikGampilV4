<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KonsultasiController extends Controller
{
    public function index()
    {
        return view('consultation.index');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'message' => 'required'
        ]);

        $waNumber = env('WHATSAPP_NUMBER', '6285123456789');
        $message = urlencode("Nama: {$validated['name']}\nTelp: {$validated['phone']}\nPesan: {$validated['message']}");
        
        return redirect("https://wa.me/{$waNumber}?text={$message}");
    }
}