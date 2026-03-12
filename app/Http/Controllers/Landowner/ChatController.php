<?php

namespace App\Http\Controllers\Landowner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display chat index page
     */
    public function index()
    {
        // Get authenticated user
        $user = Auth::user();
        
        // You can load chat data from database here
        $chats = $this->getUserChats($user);
        
        return view('landowner.chat.index', [
            'user' => $user,
            'chats' => $chats,
            'unread_count' => $this->getUnreadCount($user)
        ]);
    }

    /**
     * Get chat detail
     */
    public function show($id)
    {
        $user = Auth::user();
        
        // Validate if user can access this chat
        $chat = $this->getChatDetail($id, $user);
        
        if (!$chat) {
            abort(404, 'Chat tidak ditemukan');
        }
        
        return view('landowner.chat-detail', [
            'user' => $user,
            'chat' => $chat,
            'messages' => $this->getChatMessages($id)
        ]);
    }

    /**
     * Start new chat
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get list of potential chat partners (buyers who have booked user's venues)
        $potentialPartners = $this->getPotentialChatPartners($user);
        
        return view('landowner.chat-create', [
            'user' => $user,
            'potentialPartners' => $potentialPartners
        ]);
    }

    /**
     * Store new chat
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);
        
        $user = Auth::user();
        
        // Check if chat already exists
        $existingChat = $this->findExistingChat($user->id, $request->receiver_id);
        
        if ($existingChat) {
            // Add message to existing chat
            $this->addMessage($existingChat->id, $user->id, $request->message);
            
            return redirect()->route('landowner.chat.show', $existingChat->id)
                ->with('success', 'Pesan berhasil dikirim');
        }
        
        // Create new chat
        $chat = $this->createNewChat($user->id, $request->receiver_id, $request->message);
        
        return redirect()->route('landowner.chat.show', $chat->id)
            ->with('success', 'Chat baru berhasil dibuat');
    }

    /**
     * Send message in existing chat
     */
    public function sendMessage(Request $request, $chatId)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);
        
        $user = Auth::user();
        
        // Verify user can send message in this chat
        if (!$this->canAccessChat($chatId, $user->id)) {
            abort(403, 'Akses ditolak');
        }
        
        // Save message
        $message = $this->addMessage($chatId, $user->id, $request->message);
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'timestamp' => now()->format('H:i')
        ]);
    }

    /**
     * Get chat messages (for AJAX)
     */
    public function getMessages($chatId)
    {
        $user = Auth::user();
        
        if (!$this->canAccessChat($chatId, $user->id)) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }
        
        $messages = $this->getChatMessages($chatId);
        
        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    /**
     * Mark messages as read
     */
    public function markAsRead($chatId)
    {
        $user = Auth::user();
        
        if (!$this->canAccessChat($chatId, $user->id)) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }
        
        $this->markMessagesAsRead($chatId, $user->id);
        
        return response()->json(['success' => true]);
    }

    /**
     * Search chats
     */
    public function search(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:100'
        ]);
        
        $user = Auth::user();
        $searchTerm = $request->search;
        
        $chats = $this->searchChats($user, $searchTerm);
        
        return response()->json([
            'success' => true,
            'chats' => $chats
        ]);
    }

    /**
     * Get chat stats for sidebar/header
     */
    public function getStats()
    {
        $user = Auth::user();
        
        return response()->json([
            'success' => true,
            'stats' => [
                'total_chats' => $this->countTotalChats($user),
                'unread_messages' => $this->getUnreadCount($user),
                'active_chats' => $this->countActiveChats($user)
            ]
        ]);
    }

    // ============================================
    // HELPER METHODS (Placeholder - Implement based on your database)
    // ============================================
    
    /**
     * Get user's chats
     */
    private function getUserChats($user)
    {
        // Implement based on your database structure
        // Example:
        // return Chat::where('landowner_id', $user->id)
        //     ->orWhere('buyer_id', $user->id)
        //     ->with(['landowner', 'buyer', 'latestMessage'])
        //     ->orderBy('updated_at', 'desc')
        //     ->get();
        
        return []; // Placeholder
    }
    
    /**
     * Get chat detail
     */
    private function getChatDetail($chatId, $user)
    {
        // Implement based on your database structure
        // Example:
        // return Chat::where('id', $chatId)
        //     ->where(function($query) use ($user) {
        //         $query->where('landowner_id', $user->id)
        //               ->orWhere('buyer_id', $user->id);
        //     })
        //     ->with(['landowner', 'buyer'])
        //     ->first();
        
        return null; // Placeholder
    }
    
    /**
     * Get chat messages
     */
    private function getChatMessages($chatId)
    {
        // Implement based on your database structure
        // Example:
        // return Message::where('chat_id', $chatId)
        //     ->with('sender')
        //     ->orderBy('created_at', 'asc')
        //     ->get();
        
        return []; // Placeholder
    }
    
    /**
     * Get potential chat partners
     */
    private function getPotentialChatPartners($user)
    {
        // Get buyers who have booked this landowner's venues
        // Example:
        // return User::where('role', 'buyer')
        //     ->whereHas('bookings', function($query) use ($user) {
        //         $query->whereHas('venue', function($q) use ($user) {
        //             $q->where('landowner_id', $user->id);
        //         });
        //     })
        //     ->get();
        
        return []; // Placeholder
    }
    
    /**
     * Find existing chat between two users
     */
    private function findExistingChat($userId, $receiverId)
    {
        // Example:
        // return Chat::where(function($query) use ($userId, $receiverId) {
        //         $query->where('landowner_id', $userId)
        //               ->where('buyer_id', $receiverId);
        //     })
        //     ->orWhere(function($query) use ($userId, $receiverId) {
        //         $query->where('landowner_id', $receiverId)
        //               ->where('buyer_id', $userId);
        //     })
        //     ->first();
        
        return null; // Placeholder
    }
    
    /**
     * Create new chat
     */
    private function createNewChat($senderId, $receiverId, $initialMessage)
    {
        // Determine who is landowner and who is buyer
        $landownerId = (Auth::user()->role === 'landowner') ? $senderId : $receiverId;
        $buyerId = ($landownerId === $senderId) ? $receiverId : $senderId;
        
        // Example:
        // $chat = Chat::create([
        //     'landowner_id' => $landownerId,
        //     'buyer_id' => $buyerId,
        // ]);
        
        // $this->addMessage($chat->id, $senderId, $initialMessage);
        
        // return $chat;
        
        return null; // Placeholder
    }
    
    /**
     * Add message to chat
     */
    private function addMessage($chatId, $senderId, $content)
    {
        // Example:
        // return Message::create([
        //     'chat_id' => $chatId,
        //     'sender_id' => $senderId,
        //     'content' => $content,
        // ]);
        
        return null; // Placeholder
    }
    
    /**
     * Check if user can access chat
     */
    private function canAccessChat($chatId, $userId)
    {
        // Example:
        // return Chat::where('id', $chatId)
        //     ->where(function($query) use ($userId) {
        //         $query->where('landowner_id', $userId)
        //               ->orWhere('buyer_id', $userId);
        //     })
        //     ->exists();
        
        return true; // Placeholder for demo
    }
    
    /**
     * Mark messages as read
     */
    private function markMessagesAsRead($chatId, $userId)
    {
        // Example:
        // Message::where('chat_id', $chatId)
        //     ->where('sender_id', '!=', $userId)
        //     ->where('read_at', null)
        //     ->update(['read_at' => now()]);
    }
    
    /**
     * Search chats
     */
    private function searchChats($user, $searchTerm)
    {
        // Example:
        // return Chat::where(function($query) use ($user) {
        //         $query->where('landowner_id', $user->id)
        //               ->orWhere('buyer_id', $user->id);
        //     })
        //     ->whereHas('otherUser', function($query) use ($searchTerm) {
        //         $query->where('name', 'like', "%{$searchTerm}%")
        //               ->orWhere('email', 'like', "%{$searchTerm}%");
        //     })
        //     ->orWhereHas('messages', function($query) use ($searchTerm) {
        //         $query->where('content', 'like', "%{$searchTerm}%");
        //     })
        //     ->with(['otherUser', 'latestMessage'])
        //     ->get();
        
        return []; // Placeholder
    }
    
    /**
     * Count total chats
     */
    private function countTotalChats($user)
    {
        // Example:
        // return Chat::where('landowner_id', $user->id)
        //     ->orWhere('buyer_id', $user->id)
        //     ->count();
        
        return 0; // Placeholder
    }
    
    /**
     * Get unread message count
     */
    private function getUnreadCount($user)
    {
        // Example:
        // return Message::whereHas('chat', function($query) use ($user) {
        //         $query->where(function($q) use ($user) {
        //             $q->where('landowner_id', $user->id)
        //               ->orWhere('buyer_id', $user->id);
        //         });
        //     })
        //     ->where('sender_id', '!=', $user->id)
        //     ->where('read_at', null)
        //     ->count();
        
        return 3; // Placeholder for demo
    }
    
    /**
     * Count active chats (chats with recent messages)
     */
    private function countActiveChats($user)
    {
        // Example: Count chats with messages in last 7 days
        // return Chat::where(function($query) use ($user) {
        //         $query->where('landowner_id', $user->id)
        //               ->orWhere('buyer_id', $user->id);
        //     })
        //     ->whereHas('messages', function($query) {
        //         $query->where('created_at', '>=', now()->subDays(7));
        //     })
        //     ->count();
        
        return 0; // Placeholder
    }
}