<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Community;

/**
 * Class DashboardController
 * 
 * Menyediakan data statistik utama untuk ditampilkan pada dashboard Admin,
 * termasuk jumlah pengguna, kategori, komunitas, dan ringkasan role.
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama admin dengan ringkasan statistik.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
{
    $totalUsers = User::count();
    $totalCategories = Category::count();
    $totalCommunities = Community::count();

    // Samakan dengan blade
    $totalPenyewa = User::where('role', 'buyer')->count();
    $totalPemilik = User::where('role', 'landowner')->count();
    $totalAdmin   = User::where('role', 'admin')->count();

    $communityPublic  = Community::where('type', 'public')->count();
    $communityPrivate = Community::where('type', 'private')->count();

    return view('admin.dashboard.index', compact(
        'totalUsers',
        'totalCategories',
        'totalCommunities',
        'totalPenyewa',
        'totalPemilik',
        'totalAdmin',
        'communityPublic',
        'communityPrivate'
    ));
}

    /**
     * Menampilkan form untuk membuat resource baru.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        //
    }

    /**
     * Menyimpan resource baru ke penyimpanan.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Menampilkan resource tertentu.
     * 
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Menampilkan form untuk mengedit resource tertentu.
     * 
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Memperbarui resource tertentu di penyimpanan.
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Menghapus resource tertentu dari penyimpanan.
     * 
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        //
    }
}