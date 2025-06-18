<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use App\Models\ObatKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Obat::with('kategori')->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = ObatKategori::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'category_id' => 'required|exists:obat_kategori,id',
            'deskripsi' => 'required|string',
            'dosis' => 'required|string',
            'efek_samping' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Obat::create($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Obat berhasil ditambahkan');
    }

    /**
     * Display the specified product.
     */
    public function show(Obat $product)
    {
        $product->load('kategori');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Obat $product)
    {
        $categories = ObatKategori::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Obat $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'category_id' => 'required|exists:obat_kategori,id',
            'deskripsi' => 'required|string',
            'dosis' => 'required|string',
            'weight' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',    
            'efek_samping' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Obat berhasil diperbarui');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Obat $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Obat berhasil dihapus');
    }
}