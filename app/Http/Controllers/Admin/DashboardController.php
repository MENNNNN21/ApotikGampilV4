<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Obat;
use App\Models\ObatKategori;
use App\Models\Service;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            // Counts
            'artikelCount' => Article::count(),
            'kategoriCount' => ObatKategori::count(),
            'obatCount' => Obat::count(),
            'serviceCount' => Service::count(),

            // Latest Data
            'latestArticles' => Article::latest()->take(5)->get(),
            'latestProducts' => Obat::with('kategori')->latest()->take(5)->get(),
            'latestServices' => Service::latest()->take(5)->get(),
        ];

        return view('admin.dashboard.index', $data);
    }
}