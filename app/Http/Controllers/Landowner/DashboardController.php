<?php

namespace App\Http\Controllers\Landowner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Logika Navigasi: Halaman dashboard utama bagi pemilik, menampilkan ringkasan performa venue
        return view('landowner.home.index', [
            'title' => 'Dashboard'
        ]);
    }
}
