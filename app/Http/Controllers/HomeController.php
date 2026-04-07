<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class HomeController
 * 
 * Pengendali halaman beranda standar setelah pengguna melakukan autentikasi.
 */
class HomeController extends Controller
{
    /**
     * Membuat instance controller baru dan menetapkan middleware auth.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan dashboard aplikasi.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
}
