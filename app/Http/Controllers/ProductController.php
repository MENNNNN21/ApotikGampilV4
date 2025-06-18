<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\Obat;
use App\Models\ObatKategori;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $categories = ObatKategori::all();
        return view('products.index', compact('categories'));
    }

    public function category($slug)
    {
        $category = ObatKategori::where('slug', $slug)->firstOrFail();
        $medicines = Obat::where('category_id', $category->id)->get();
        return view('products.category', compact('category', 'medicines'));
    }
    

    public function show($id)
    {
        $medicine = Obat::findOrFail($id);
        return view('products.show', compact('medicine'));
    }
}