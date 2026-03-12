<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'user_one_id',
        'user_two_id',
        'community_id',
        'type',
        'last_message_at'
    ];

    protected $casts = [
        'last_message_at' => 'datetime'
    ];

    // Relasi
    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

    // Helper Methods
    public function getOtherUser($userId)
    {
        // ⚠️ PENTING: Untuk grup, return Community object (bukan User)
        // Pastikan di Blade check tipe object-nya
        if ($this->type === 'community') {
            return $this->community;
        }
        return $this->user_one_id == $userId ? $this->userTwo : $this->userOne;
    }

    public function isParticipant($userId)
    {
        if ($this->type === 'community') {
            return $this->community->members()->where('user_id', $userId)->exists();
        }
        return $this->user_one_id == $userId || $this->user_two_id == $userId;
    }

    public function unreadCount($userId)
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }

    public function markAsRead($userId)
    {
        // For 1-on-1
        if ($this->type !== 'community') {
            $this->messages()
                ->where('sender_id', '!=', $userId)
                ->where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);
        }
        // For community, we can't easily mark messages as read for just this user without pivot table.
        // We'll skip for now to avoid side effects (marking read for everyone).
    }

    // Scope
    public function scopeForUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
                // Personal chats
                $q->where(function($personalQuery) use ($userId) {
                    $personalQuery->where('type', 'personal')
                        ->where(function($userQuery) use ($userId) {
                            $userQuery->where('user_one_id', $userId)
                                      ->orWhere('user_two_id', $userId);
                        });
                })
                // Community chats (fix: pakai community_members bukan community_user)
                ->orWhere(function($communityQuery) use ($userId) {
                    $communityQuery->where('type', 'community')
                        ->whereHas('community.members', function($memberQuery) use ($userId) {
                            $memberQuery->where('user_id', $userId);
                        });
                });
            });
    }

    // Get or Create Conversation
    public static function findOrCreateConversation($userOneId, $userTwoId)
    {
        // ✅ FIX: Ganti 'private' jadi 'personal' untuk konsistensi
        $conversation = self::where('type', 'personal')
            ->where(function($query) use ($userOneId, $userTwoId) {
                $query->where(function($q) use ($userOneId, $userTwoId) {
                    $q->where('user_one_id', $userOneId)
                      ->where('user_two_id', $userTwoId);
                })
                ->orWhere(function($q) use ($userOneId, $userTwoId) {
                    $q->where('user_one_id', $userTwoId)
                      ->where('user_two_id', $userOneId);
                });
            })
            ->first();

        if (!$conversation) {
            $conversation = self::create([
                'user_one_id' => $userOneId,
                'user_two_id' => $userTwoId,
                'type' => 'personal', // ✅ Konsisten pakai 'personal'
                'last_message_at' => now()
            ]);
        }

        return $conversation;
    }

    public static function findOrCreateCommunityConversation($communityId)
    {
        $conversation = self::where('type', 'community')
            ->where('community_id', $communityId)
            ->first();

        if (!$conversation) {
            $conversation = self::create([
                'community_id' => $communityId,
                'type' => 'community',
                'last_message_at' => now()
            ]);
        }
        
        return $conversation;
    }
}