<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function chat()
    {
        $user = Auth::user();

        return view('buyer.chat.index', [
            'title' => 'Chat',
            'user' => $user
        ]);
    }
}
