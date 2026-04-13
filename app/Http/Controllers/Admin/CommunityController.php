<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;

/**
 * Class CommunityController
 * 
 * Mengelola data komunitas yang terdaftar di dalam sistem (Hanya Baca untuk Admin).
 * Admin dapat melihat daftar komunitas dan detailnya.
 */
class CommunityController extends Controller
{
    /**
     * Menampilkan daftar semua komunitas beserta pembuatnya.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $communities = Community::with('creator')->orderBy('created_at', 'desc')->get();
        return view('admin.community.index', compact('communities'));
    }

    /**
     * Menampilkan detail informasi komunitas tertentu.
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $communities = Community::with('creator')->findOrFail($id);
        return view('admin.community.show', compact('communities'));
    }

    // Hapus semua method lainnya (store, update, destroy)
    // Karena hanya read-only

    public function ban($id)
    {
        $community = Community::findOrFail($id);

        // kalau pakai is_banned
        $community->is_banned = 1;

        // kalau pakai status (kalau tadi pilih ini)
        // $community->status = 'banned';

        $community->save();

        return response()->json([
            'success' => true,
            'message' => 'Community berhasil dibanned'
        ]);
    }
}