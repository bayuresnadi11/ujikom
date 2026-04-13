<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'username',
        'password',
        'role',
        'can_switch_to_landowner',
        'landowner_approved_at',
        'avatar',
        'bank_name', // Tambahkan ini
        'account_number', // Tambahkan ini
        'account_holder_name', // Tambahkan ini
        'address',
        'business_name',
        'gender',
        'birthdate',
        'created_by',
        'email', // Tambahkan jika ada field email
        'email_verified_at',
        'background', // Background profile image
        'landowner_background', // Background landowner profile image
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthdate' => 'date',
        'can_switch_to_landowner' => 'boolean',
        'landowner_approved_at' => 'datetime',
    ];

    // Scopes
    public function scopeWithRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeBuyers($query)
    {
        return $query->where('role', 'buyer');
    }

    public function scopeSellers($query)
    {
        return $query->where('role', 'seller');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    // Relationships
    public function venues()
    {
        return $this->hasMany(Venue::class, 'created_by');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function communitiesCreated()
    {
        return $this->hasMany(Community::class, 'created_by');
    }

    public function communityMembers()
    {
        return $this->hasMany(CommunityMember::class);
    }

    public function roleRequests()
    {
        return $this->hasMany(RoleRequest::class);
    }

    public function communityRequests()
    {
        return $this->hasMany(CommunityRequest::class);
    }

    public function playTogetherCreated()
    {
        return $this->hasMany(PlayTogether::class, 'created_by');
    }

    public function playTogetherParticipants()
    {
        return $this->hasMany(PlayTogetherParticipant::class);
    }

    public function sparringCreated()
    {
        return $this->hasMany(Sparring::class, 'created_by');
    }

    public function sparringParticipants()
    {
        return $this->hasMany(SparringParticipant::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Booking::class);
    }
    
    public function otps()
    {
        return $this->hasMany(Otp::class);
    }

    public function communityInvitations()
    {
        return $this->hasMany(CommunityInvitation::class, 'invited_user_id');
    }

    public function role_requests()
    {
        return $this->hasMany(\App\Models\RoleRequest::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by'); // Perbaiki typo 'created_lby' menjadi 'created_by'
    }

    public function cashiers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    // Cek apakah user sudah pernah diapprove jadi landowner
    public function hasApprovedLandownerRequest()
    {
        return $this->roleRequests()
                   ->where('requested_role', 'landowner')
                   ->where('status', 'approved')
                   ->exists();
    }

    // Cek apakah user punya pending request
    public function hasPendingLandownerRequest()
    {
        return $this->roleRequests()
                   ->where('requested_role', 'landowner')
                   ->where('status', 'pending')
                   ->exists();
    }

    // Get approved landowner request
    public function getApprovedLandownerRequest()
    {
        return $this->roleRequests()
                   ->where('requested_role', 'landowner')
                   ->where('status', 'approved')
                   ->latest()
                   ->first();
    }

    // Cek apakah user adalah landowner
    public function isLandowner()
    {
        return $this->role === 'landowner';
    }

    // ==================== NEW: Session-based Role Switching ====================
    
    // === ROLE SWITCHING HELPER METHODS ===

    /**
     * Get the current active role (Just a wrapper for standard role)
     */
    public function getActiveRole()
    {
        return $this->role;
    }
    
    // Check if user can switch to landowner
    public function canSwitchToLandowner()
    {
        return $this->can_switch_to_landowner === true || $this->can_switch_to_landowner === 1;
    }
    
    // Get latest landowner request
    public function getLatestLandownerRequest()
    {
        return $this->roleRequests()
                   ->where('requested_role', 'landowner')
                   ->latest()
                   ->first();
    }
    
    /**
     * Switch the user's role (Persisted to DB)
     */
    public function switchRole($newRole)
    {
        // Validation: Cannot switch to landowner if not allowed
        if ($newRole === 'landowner' && !$this->canSwitchToLandowner()) {
             return false;
        }

        // Direct update to the 'role' column
        // This is now the Single Source of Truth
        $this->update(['role' => $newRole]);
        
        // No session update needed as Auth::user()->role is re-fetched
        
        return true;
    }
    
    // Legacy methods (kept for backward compatibility)
    public function switchToBuyer()
    {
        return $this->switchRole('buyer');
    }

    public function switchToLandowner()
    {
        return $this->switchRole('landowner');
    }

    // Relasi conversations
    public function conversations()
    {
        return Conversation::where('user_one_id', $this->id)
            ->orWhere('user_two_id', $this->id)
            ->orderBy('last_message_at', 'desc')
            ->get();
    }

    // Check if user is buyer
    public function isBuyer()
    {
        return $this->role === 'buyer';
    }


    // Check if user is admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Di User model
    public function unreadMessagesCount()
    {
        return cache()->remember(
            "user.{$this->id}.unread_messages",
            60, // 1 minute
            function() {
                return Message::whereHas('conversation', function($query) {
                        $query->where('user_one_id', $this->id)
                            ->orWhere('user_two_id', $this->id);
                    })
                    ->where('sender_id', '!=', $this->id)
                    ->where('is_read', false)
                    ->count();
            }
        );
    }

// Clear cache saat ada message baru
// Di ChatBox->sendMessage()

    /**
     * Get the notifications for the user.
     * Overriding Laravel's default Notifiable notifications to use custom Notification model.
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Get the unread notifications for the user.
     */
    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    /**
     * Get the read notifications for the user.
     */
    public function readNotifications()
    {
        return $this->notifications()->whereNotNull('read_at');
    }

    /**
     * Get the recent notifications (last 30 days).
     */
    public function recentNotifications($limit = 10)
    {
        return $this->notifications()
                    ->where('created_at', '>=', Carbon::now()->subDays(30))
                    ->limit($limit)
                    ->get();
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllNotificationsAsRead()
    {
        return $this->unreadNotifications()->update(['read_at' => now()]);
    }

    /**
     * Mark specific notification as read.
     */
    public function markNotificationAsRead($notificationId)
    {
        $notification = $this->notifications()->find($notificationId);
        
        if ($notification && !$notification->read_at) {
            $notification->update(['read_at' => now()]);
            return true;
        }
        
        return false;
    }

    /**
     * Get unread notifications count (cached for performance).
     */
    public function getUnreadNotificationsCountAttribute()
    {
        // Cache untuk 30 detik untuk mengurangi query database
        return cache()->remember(
            "user_{$this->id}_unread_notifications_count",
            30,
            fn() => $this->unreadNotifications()->count()
        );
    }

    /**
     * Create a notification for this user.
     */
    public function createNotification($data)
    {
        return $this->notifications()->create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'type' => $data['type'] ?? 'default',
            'data' => [
                'title' => $data['title'] ?? 'Notifikasi Baru',
                'message' => $data['message'] ?? '',
                'action_url' => $data['action_url'] ?? null,
                'action_text' => $data['action_text'] ?? 'Lihat',
                'icon' => $data['icon'] ?? 'bell',
                'color' => $data['color'] ?? 'primary',
                'priority' => $data['priority'] ?? 'normal',
                ...($data['data'] ?? [])
            ],
            'read_at' => null,
            'created_at' => now(),
        ]);
    }

    /**
     * Check if user has unread notifications.
     */
    public function hasUnreadNotifications()
    {
        return $this->unreadNotifications()->exists();
    }

    /**
     * Get latest notification.
     */
    public function getLatestNotification()
    {
        return $this->notifications()->latest()->first();
    }

    /**
     * Get notifications by type.
     */
    public function notificationsByType($type)
    {
        return $this->notifications()->where('type', $type)->get();
    }

    /**
     * Clear notification cache.
     */
    public function clearNotificationCache()
    {
        cache()->forget("user_{$this->id}_unread_notifications_count");
    }

    /**
     * Get user's role with formatted display.
     */
    public function getFormattedRoleAttribute()
    {
        $roles = [
            'buyer' => 'Penyewa',
            'seller' => 'Pemilik Lapangan',
            'admin' => 'Administrator',
            'cashier' => 'Kasir',
        ];
        
        return $roles[$this->role] ?? ucfirst($this->role);
    }

    /**
     * Check if user has specific role.
     */
    public function hasRole($role)
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        
        return $this->role === $role;
    }

    /**
     * Get avatar URL or default.
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        // Default avatar based on gender
        if ($this->gender === 'female') {
            return asset('images/default-avatar-female.png');
        }
        
        return asset('images/default-avatar-male.png');
    }

    /**
     * Get user's age from birthdate.
     */
    public function getAgeAttribute()
    {
        if (!$this->birthdate) {
            return null;
        }
        
        return Carbon::parse($this->birthdate)->age;
    }

    /**
     * Get user's initials for avatar placeholder.
     */
    public function getInitialsAttribute()
    {
        $nameParts = explode(' ', $this->name);
        $initials = '';
        
        foreach ($nameParts as $part) {
            if (!empty($part)) {
                $initials .= strtoupper($part[0]);
                if (strlen($initials) >= 2) {
                    break;
                }
            }
        }
        
        return $initials ?: 'US';
    }

    // Event listeners untuk clear cache
    protected static function booted()
    {
        static::updated(function ($user) {
            // Clear notification cache ketika user diupdate
            $user->clearNotificationCache();
        });
    }

    /**
     * Get total unread chat messages count for this user
     */
    public function unreadChatCount()
    {
        // Get all conversations where user is participant
        $conversationIds = \App\Models\Conversation::where(function($q) {
            // Personal chats
            $q->where(function($personalQuery) {
                $personalQuery->where('type', 'personal')
                    ->where(function($userQuery) {
                        $userQuery->where('user_one_id', $this->id)
                                ->orWhere('user_two_id', $this->id);
                    });
            })
            // Community chats
            ->orWhere(function($communityQuery) {
                $communityQuery->where('type', 'community')
                    ->whereHas('community.members', function($memberQuery) {
                        $memberQuery->where('user_id', $this->id);
                    });
            });
        })->pluck('id');

        // Count unread messages in those conversations
        return \App\Models\Message::whereIn('conversation_id', $conversationIds)
            ->where('sender_id', '!=', $this->id)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Alternative: Get unread chat count from conversations
     */
    public function unreadConversationsCount()
    {
        return \App\Models\Conversation::where(function($q) {
                $q->where('user_one_id', $this->id)
                ->orWhere('user_two_id', $this->id);
            })
            ->whereHas('messages', function($q) {
                $q->where('sender_id', '!=', $this->id)
                ->where('is_read', 0);
            })
            ->count();
    }
}