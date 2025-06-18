<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();
        return view('services.index', compact('services'));
    }

    public function redirectToWhatsApp($id)
    {
        $service = Service::findOrFail($id);
        $waTemplate = urlencode($service->whatsapp_template);
        $waNumber = env('WHATSAPP_NUMBER', '6285123456789');
        return redirect("https://wa.me/{$waNumber}?text={$waTemplate}");
    }
}

