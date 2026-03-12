<?php

namespace App\Http\Controllers\Landowner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('landowner.home.index', [
            'title' => 'Dashboard'
        ]);
    }
}
