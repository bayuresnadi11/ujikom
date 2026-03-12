<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class ChatList extends Component
{
    public $activeTab = 'chats'; // chats or discover
    public $filter = 'all'; // all, unread, group, support
    public $search = '';

    protected $listeners = ['conversationCreated' => '$refresh'];

    public function updatedSearch($value)
    {
        // Dispatch event to discover-users component whenever search changes
        $this->dispatch('searchUpdated', search: $value);
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        
        // Reset search when switching tabs
        if ($tab === 'chats') {
            $this->search = '';
            $this->dispatch('searchUpdated', search: '');
        }
    }

    public function render()
    {
        $query = Conversation::query()
            ->where(function($q) {
                // Personal chats (1-on-1)
                $q->where(function($personalQuery) {
                    $personalQuery->where('type', 'personal')
                        ->where(function($userQuery) {
                            $userQuery->where('user_one_id', Auth::id())
                                      ->orWhere('user_two_id', Auth::id());
                        });
                })
                // Group/Community chats
                ->orWhere(function($groupQuery) {
                    $groupQuery->where('type', 'community')
                        ->whereHas('community.members', function($memberQuery) {
                            $memberQuery->where('user_id', Auth::id());
                        });
                });
            })
            ->with([
                'userOne', 
                'userTwo', 
                'lastMessage.sender', 
                'field',
                'community.members' // ✅ Load community beserta members-nya
            ])
            ->orderBy('last_message_at', 'desc');

        // Apply search filter for chats
        if ($this->activeTab === 'chats' && $this->search) {
            $query->where(function($q) {
                $searchTerm = '%' . $this->search . '%';
                
                // Search in personal chats
                $q->where(function($personalSearch) use ($searchTerm) {
                    $personalSearch->whereHas('userOne', function($userQuery) use ($searchTerm) {
                        $userQuery->where('name', 'LIKE', $searchTerm);
                    })
                    ->orWhereHas('userTwo', function($userQuery) use ($searchTerm) {
                        $userQuery->where('name', 'LIKE', $searchTerm);
                    });
                })
                // Search in group chats (community name)
                ->orWhereHas('community', function($communityQuery) use ($searchTerm) {
                    $communityQuery->where('name', 'LIKE', $searchTerm);
                })
                // Search in last message
                ->orWhereHas('lastMessage', function($msgQuery) use ($searchTerm) {
                    $msgQuery->where('message', 'LIKE', $searchTerm);
                });
            });
        }

        // Apply filter
        if ($this->filter === 'unread') {
            // Filter conversations that have unread messages for current user
            $query->whereHas('messages', function($q) {
                $q->where('sender_id', '!=', Auth::id())
                  ->where('is_read', 0);
            });
        } elseif ($this->filter === 'group') {
            // Only show community/group chats
            $query->where('type', 'community');
        } elseif ($this->filter === 'support') {
            // Only show chats with admin users (personal chats only)
            $query->where('type', 'personal')
                  ->where(function($q) {
                      $q->whereHas('userOne', function($userQuery) {
                          $userQuery->where('role', 'admin');
                      })->orWhereHas('userTwo', function($userQuery) {
                          $userQuery->where('role', 'admin');
                      });
                  });
        }

        $conversations = $query->get();

        return view('livewire.chat.chat-list', [
            'conversations' => $conversations
        ]);
    }
}