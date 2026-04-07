<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Class ChatController
 * 
 * Mengelola sistem percakapan (chat) antar pengguna, termasuk menampilkan daftar chat,
 * menampilkan isi percakapan tertentu, dan membuat percakapan baru.
 */
class ChatController extends Controller
{
    /**
     * Menampilkan halaman utama chat sesuai dengan role pengguna.
     * 
     * @return \Illuminate\View\View
     */
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

    /**
     * Menampilkan isi percakapan tertentu antar pengguna.
     * 
     * @param \App\Models\Conversation $conversation
     * @return \Illuminate\View\View
     */
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

    /**
     * Membuat atau mencari percakapan baru dengan pengguna tertentu.
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
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