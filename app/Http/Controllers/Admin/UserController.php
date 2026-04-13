<?php
// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

/**
 * Class UserController
 * 
 * Mengelola daftar seluruh pengguna yang terdaftar di sistem dari sisi Admin.
 */
class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna beserta informasinya.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua data user dari database
        // Diurutkan berdasarkan waktu dibuat (terbaru di atas)
        $users = User::orderBy('created_at', 'desc')->get();

        // Kirim data users ke view admin.user.index
        return view('admin.user.index', compact('users'));
    }
}