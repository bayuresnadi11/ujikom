<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class Controller
 * 
 * Base controller untuk aplikasi Sewa Lapangan.
 * Menyediakan fungsionalitas dasar seperti validasi dan otorisasi.
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
