<?php

namespace App\Http\Controllers\Landowner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TipsController extends Controller
{
    /**
     * Display tips management page for landowners
     */
    public function index()
    {
        // Logika Penyajian Data: Menyediakan daftar tips statis untuk membantu pemilik mengelola venue
        $tips = [
            [
                'category' => 'pricing',
                'title' => 'Analisis Harga Pasar',
                'content' => 'Selalu pantau harga venue serupa di wilayah Anda. Tentukan harga kompetitif dengan mempertimbangkan fasilitas yang Anda tawarkan.',
                'read_time' => '5 min read'
            ],
            [
                'category' => 'pricing',
                'title' => 'Diskon Musiman',
                'content' => 'Tawarkan diskon pada musim sepi untuk meningkatkan okupansi. Ini lebih baik daripada venue kosong.',
                'read_time' => '3 min read'
            ],
            [
                'category' => 'marketing',
                'title' => 'Foto Berkualitas Tinggi',
                'content' => 'Investasi dalam foto profesional dapat meningkatkan minat penyewa hingga 70%. Tampilkan semua sudut venue Anda.',
                'read_time' => '4 min read'
            ],
            [
                'category' => 'marketing',
                'title' => 'Manfaatkan Media Sosial',
                'content' => 'Promosikan venue Anda di Instagram dan Facebook dengan hashtag lokal. Tampilkan testimoni dari penyewa sebelumnya.',
                'read_time' => '6 min read'
            ],
            [
                'category' => 'facilities',
                'title' => 'Fasilitas Tambahan',
                'content' => 'Tawarkan fasilitas tambahan seperti sound system, proyektor, atau dekorasi dengan biaya extra untuk meningkatkan pendapatan.',
                'read_time' => '3 min read'
            ],
            [
                'category' => 'facilities',
                'title' => 'Fasilitas Dasar',
                'content' => 'Pastikan fasilitas dasar seperti toilet, parkir, dan AC berfungsi dengan baik. Ini adalah hal pertama yang diperhatikan penyewa.',
                'read_time' => '4 min read'
            ]
        ];

        // Definisi kategori tips untuk filter di sisi client/UI
        $categories = [
            'all' => 'Semua Tips',
            'pricing' => 'Penentuan Harga',
            'marketing' => 'Pemasaran',
            'facilities' => 'Fasilitas',
            'maintenance' => 'Perawatan',
            'customer' => 'Layanan Pelanggan'
        ];

        return view('landowner.tips.index', compact('tips', 'categories'));
    }

    /**
     * Get tips by category (for AJAX requests)
     */
    public function getByCategory($category)
    {
        // This would typically query database
        $tips = []; // Add logic to filter by category
        
        return response()->json([
            'success' => true,
            'tips' => $tips,
            'category' => $category
        ]);
    }

    /**
     * Save tip to bookmarks
     */
    public function bookmark(Request $request)
    {
        $request->validate([
            'tip_id' => 'required|integer'
        ]);

        // Add logic to bookmark tip for current user
        
        return response()->json([
            'success' => true,
            'message' => 'Tip berhasil disimpan ke bookmark'
        ]);
    }

    /**
     * Remove tip from bookmarks
     */
    public function removeBookmark(Request $request)
    {
        $request->validate([
            'tip_id' => 'required|integer'
        ]);

        // Add logic to remove bookmark
        
        return response()->json([
            'success' => true,
            'message' => 'Tip berhasil dihapus dari bookmark'
        ]);
    }
}