<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Halaman chat utama
    public function index()
    {
        $user = Auth::user();
        
        // Tentukan view berdasarkan role
        if ($user->role === 'landowner') {
            return view('landowner.chat.index');
        } elseif ($user->role === 'buyer') {
            return view('buyer.chat.index');
        } elseif ($user->role === 'admin') {
            return view('admin.chat.index');
        }
        
        return view('chat.index');
    }

    // Show specific conversation
    public function show(Conversation $conversation)
    {
        // Check if user is participant
        if (!$conversation->isParticipant(Auth::id())) {
            abort(403, 'Unauthorized');
        }

        $user = Auth::user();
        
        // Mark as read
        $conversation->markAsRead(Auth::id());
        
        // Return view berdasarkan role dengan conversation ID
        if ($user->role === 'landowner') {
            return view('landowner.chat.index', [
                'conversationId' => $conversation->id
            ]);
        } elseif ($user->role === 'buyer') {
            return view('buyer.chat.index', [
                'conversationId' => $conversation->id
            ]);
        } elseif ($user->role === 'admin') {
            return view('admin.chat.index', [
                'conversationId' => $conversation->id
            ]);
        }
        
        return view('chat.index', [
            'conversationId' => $conversation->id
        ]);
    }

    // Create new chat with specific user
    public function create(User $user)
    {
        // Don't allow chat with self
        if ($user->id === Auth::id()) {
            return redirect()->route('chat.index')
                ->with('error', 'Tidak bisa chat dengan diri sendiri');
        }

        // Find or create conversation
        $conversation = Conversation::findOrCreateConversation(
            Auth::id(),
            $user->id
        );

        return redirect()->route('chat.show', $conversation->id);
    }
}