<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class CategoryController
 * 
 * Mengelola kategori lapangan (misalnya: Futsal, Badminton, Basket) yang tersedia di sistem.
 * Mencakup operasi CRUD (Create, Read, Update, Delete) untuk kategori.
 */
class CategoryController extends Controller
{
    /**
     * Menampilkan daftar semua kategori lapangan.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $kategoris = Category::orderBy('created_at', 'desc')->get();
        return view('admin.category.index', compact('kategoris'));
    }

    /**
     * Menyimpan kategori baru ke dalam database.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = [
            'category_name' => $request->nama_kategori,
            'description' => $request->deskripsi,
        ];

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $logoPath;
        }

        Category::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan!'
        ]);
    }

    /**
     * Menampilkan detail kategori tertentu berdasarkan ID (untuk AJAX).
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $kategori = Category::findOrFail($id);
        
        // Map field untuk response JSON (dari database ke view)
        $response = [
            'id' => $kategori->id,
            'nama_kategori' => $kategori->category_name,
            'deskripsi' => $kategori->description,
            'logo' => $kategori->logo,
        ];
        
        return response()->json($response);
    }

    /**
     * Memperbarui data kategori tertentu di database.
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $kategori = Category::findOrFail($id);
        $data = [
            'category_name' => $request->nama_kategori,
            'description' => $request->deskripsi,
        ];

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($kategori->logo) {
                Storage::disk('public')->delete($kategori->logo);
            }
            
            $logoPath = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $logoPath;
        }

        $kategori->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui!'
        ]);
    }

    /**
     * Menghapus kategori tertentu dari database.
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $kategori = Category::findOrFail($id);
        
        // Hapus logo jika ada
        if ($kategori->logo) {
            Storage::disk('public')->delete($kategori->logo);
        }

        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus!'
        ]);
    }
}