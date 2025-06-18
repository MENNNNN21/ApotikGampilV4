<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori as Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:obat_kategori,name',
            'slug' => 'nullable|string|max:255|unique:obat_kategori,slug',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah digunakan.',
            'slug.unique' => 'Slug sudah digunakan.',
        ]);

        try {
            $slug = $request->slug ?: Str::slug($request->name);
            
            // Ensure slug is unique
            $originalSlug = $slug;
            $counter = 1;
            while (Category::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            Category::create([
                'name' => $request->name,
                'slug' => $slug,
            ]);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Kategori berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Gagal menambahkan kategori: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('obat_kategori', 'name')->ignore($category->id)
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('obat_kategori', 'slug')->ignore($category->id)
            ],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah digunakan.',
            'slug.unique' => 'Slug sudah digunakan.',
        ]);

        try {
            $slug = $request->slug ?: Str::slug($request->name);
            
            // Ensure slug is unique (excluding current category)
            $originalSlug = $slug;
            $counter = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $category->update([
                'name' => $request->name,
                'slug' => $slug,
            ]);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Kategori berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Gagal memperbarui kategori: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            // Check if category has products
            $productCount = $category->products()->count();
            
            if ($productCount > 0) {
                return redirect()->route('admin.categories.index')
                    ->with('error', "Tidak dapat menghapus kategori karena masih memiliki {$productCount} produk.");
            }

            $categoryName = $category->name;
            $category->delete();

            return redirect()->route('admin.categories.index')
                ->with('success', "Kategori '{$categoryName}' berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Gagal menghapus kategori: ' . $e->getMessage());
        }
    }

    /**
     * Get categories for API/AJAX requests
     */
    public function apiIndex()
    {
        $categories = Category::orderBy('name', 'asc')->get(['id', 'name', 'slug']);
        
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}