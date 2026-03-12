<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MenuController extends Controller
{
    public function menu()
    {
        $user = Auth::user();

        // Get page history from session
        $pageHistory = session('page_history', []);

        // Add time ago information to each page
        foreach ($pageHistory as &$page) {
            $page['time_ago'] = Carbon::createFromTimestamp($page['timestamp'])->diffForHumans();
        }

        return view('buyer.menu.index', [
            'title' => 'Menu',
            'user' => $user,
            'pageHistory' => $pageHistory
        ]);
    }
}
