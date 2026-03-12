<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get notifications directly from database without soft deletes check
        $notifications = DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', 'App\Models\User')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($notification) {
                $data = json_decode($notification->data, true) ?? [];
                
                // System notifications should use icons, not avatars/initials
                $systemTypes = ['payment_success', 'payment_received', 'booking_confirmed', 'booking_cancelled', 'system', 'announcement', 'reminder'];
                $isSystem = in_array($notification->type, $systemTypes);

                // Avatar Logic
                $avatarUrl = null;
                $initials = null;
                $avatarColor = null;

                if (isset($data['community_id'])) {
                    $community = \App\Models\Community::find($data['community_id']);
                    if ($community && $community->logo) {
                        $avatarUrl = asset('storage/' . $community->logo);
                    }
                }
                
                if (!$avatarUrl) {
                    $senderId = $data['sender_id'] ?? $data['user_id'] ?? $data['host_id'] ?? null;
                    // Don't show avatar/initials for system notifications
                    if ($senderId && !$isSystem) {
                        $sender = \App\Models\User::find($senderId);
                        if ($sender) {
                            if ($sender->avatar) {
                                $avatarUrl = asset('storage/' . $sender->avatar);
                            } else {
                                // Initials logic like in members/index.blade.php
                                $name = $sender->name ?? 'User';
                                $words = explode(' ', $name);
                                if (count($words) >= 2) {
                                    $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                                } else {
                                    $initials = strtoupper(substr($name, 0, 2));
                                }
                                
                                $colors = ['#0A5C36', '#2ecc71', '#3498db', '#9b59b6', '#e74c3c', '#f39c12'];
                                $colorIndex = crc32($name) % count($colors);
                                $avatarColor = $colors[$colorIndex];
                            }
                        }
                    }
                }

                return [
                    'id' => $notification->id,
                    'title' => $data['title'] ?? $this->getDefaultTitle($notification->type),
                    'message' => $data['message'] ?? '',
                    'type' => $notification->type,
                    'is_read' => $notification->read_at !== null,
                    'created_at' => Carbon::parse($notification->created_at),
                    'icon' => $isSystem ? $this->getNotificationIcon($notification->type) : ($data['icon'] ?? $this->getNotificationIcon($notification->type)),
                    'source' => 'laravel',
                    'action_url' => $data['action_url'] ?? null,
                    'action_text' => $data['action_text'] ?? 'Lihat Detail',
                    'color' => $isSystem ? $this->getNotificationColor($notification->type) : ($data['color'] ?? $this->getNotificationColor($notification->type)),
                    'priority' => $data['priority'] ?? 'normal',
                    'avatar_url' => $avatarUrl,
                    'initials' => $initials,
                    'avatar_color' => $avatarColor,
                    'data' => $data
                ];
            })
            ->toArray();

        // Count unread notifications without soft deletes check
        $unreadCount = DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', 'App\Models\User')
            ->whereNull('read_at')
            ->count();
            
        // Get user's communities for the community filter header
        // 1. Get all unique community IDs from notifications
        $notifiedCommunityIds = collect($notifications)
            ->pluck('data.community_id')
            ->filter()
            ->unique()
            ->values();

        // 2. Get user's active communities
        $activeCommunityIds = \DB::table('community_members')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->pluck('community_id');

        // 3. Merge both and fetch community models
        $communityIds = $notifiedCommunityIds->merge($activeCommunityIds)->unique();

        $userCommunities = \App\Models\Community::whereIn('id', $communityIds)
            ->with('category')
            ->get();

        // Sort communities by latest notification (push communities with new notifications to the front)
        $userCommunities = $userCommunities->sortBy(function ($community) use ($notifiedCommunityIds) {
            $index = $notifiedCommunityIds->search($community->id);
            return $index === false ? 999 : $index;
        })->values();

        return view('buyer.notifications.index', [
            'allNotifications' => $notifications,
            'unreadCount' => $unreadCount,
            'userCommunities' => $userCommunities
        ]);
    }

    private function getDefaultTitle($type)
    {
        $titles = [
            'community_removed' => 'Dikeluarkan dari Komunitas',
            'community_role_changed' => 'Role Diubah',
            'community_message' => 'Pesan Komunitas',
            'community_invitation' => 'Undangan Komunitas',
            'community_request_approved' => 'Permintaan Diterima',
            'community_request_rejected' => 'Permintaan Ditolak',
            'community_request_pending' => 'Permintaan Bergabung',
            'booking_confirmed' => 'Booking Dikonfirmasi',
            'booking_cancelled' => 'Booking Dibatalkan',
            'payment_success' => 'Pembayaran Berhasil',
            'payment_received' => 'Pembayaran Diterima',
            'system' => 'Notifikasi Sistem',
            'announcement' => 'Pengumuman',
            'reminder' => 'Pengingat',
            'main_bareng_invitation' => 'Undangan Main Bareng',
            'main_bareng_invitation_accepted' => 'Undangan Diterima',
            'main_bareng_invitation_rejected' => 'Undangan Ditolak',
            'main_bareng_join_request' => 'Permintaan Bergabung',
            'main_bareng_join_approved' => 'Permintaan Diterima',
            'main_bareng_join_rejected' => 'Permintaan Ditolak',
        ];

        return $titles[$type] ?? 'Notifikasi';
    }

    private function getNotificationIcon($type)
    {
        $icons = [
            'community_removed' => 'fas fa-user-slash',
            'community_role_changed' => 'fas fa-user-shield',
            'community_message' => 'fas fa-comment-dots',
            'community_invitation' => 'fas fa-envelope',
            'community_request_approved' => 'fas fa-check-circle',
            'community_request_rejected' => 'fas fa-times-circle',
            'community_request_pending' => 'fas fa-users',
            'booking_confirmed' => 'fas fa-calendar-check',
            'booking_cancelled' => 'fas fa-calendar-times',
            'payment_success' => 'fas fa-money-bill-wave',
            'payment_received' => 'fas fa-money-bill-wave',
            'system' => 'fas fa-info-circle',
            'announcement' => 'fas fa-bullhorn',
            'reminder' => 'fas fa-clock',
            'main_bareng_invitation' => 'fas fa-futbol',
            'main_bareng_invitation_accepted' => 'fas fa-check-circle',
            'main_bareng_invitation_rejected' => 'fas fa-times-circle',
            'main_bareng_join_request' => 'fas fa-user-plus',
            'main_bareng_join_approved' => 'fas fa-check-circle',
            'main_bareng_join_rejected' => 'fas fa-times-circle',
        ];

        return $icons[$type] ?? 'fas fa-bell';
    }

    private function getNotificationColor($type)
    {
        $colors = [
            'community_removed' => 'danger',
            'community_role_changed' => 'warning',
            'community_message' => 'info',
            'community_invitation' => 'success',
            'community_request_approved' => 'success',
            'community_request_rejected' => 'danger',
            'community_request_pending' => 'info',
            'booking_confirmed' => 'success',
            'booking_cancelled' => 'warning',
            'payment_success' => 'success',
            'payment_received' => 'success',
            'system' => 'info',
            'announcement' => 'warning',
            'reminder' => 'info',
            'main_bareng_invitation' => 'success',
            'main_bareng_invitation_accepted' => 'success',
            'main_bareng_invitation_rejected' => 'danger',
            'main_bareng_join_request' => 'info',
            'main_bareng_join_approved' => 'success',
            'main_bareng_join_rejected' => 'danger',
        ];

        return $colors[$type] ?? 'primary';
    }

    public function markAsRead(Request $request, $id)
    {
        $user = auth()->user();

        $updated = DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', 'App\Models\User')
            ->update(['read_at' => now()]);

        if ($updated) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    }

    public function markAllAsRead(Request $request)
    {
        $user = auth()->user();

        DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', 'App\Models\User')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $user = auth()->user();

        $unreadCount = DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', 'App\Models\User')
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'success' => true,
            'count' => $unreadCount
        ]);
    }

    public function getNotifications()
    {
        $user = auth()->user();

        // Get unread notifications
        $notifications = DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', 'App\Models\User')
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($notification) {
                $data = json_decode($notification->data, true) ?? [];
                
                // System notifications should use icons, not avatars/initials
                $systemTypes = ['payment_success', 'payment_received', 'booking_confirmed', 'booking_cancelled', 'system', 'announcement', 'reminder'];
                $isSystem = in_array($notification->type, $systemTypes);

                // Avatar Logic
                $avatarUrl = null;
                $initials = null;
                $avatarColor = null;

                if (isset($data['community_id'])) {
                    $community = \App\Models\Community::find($data['community_id']);
                    if ($community && $community->logo) {
                        $avatarUrl = asset('storage/' . $community->logo);
                    }
                }
                
                if (!$avatarUrl) {
                    $senderId = $data['sender_id'] ?? $data['user_id'] ?? $data['host_id'] ?? null;
                    if ($senderId && !$isSystem) {
                        $sender = \App\Models\User::find($senderId);
                        if ($sender) {
                            if ($sender->avatar) {
                                $avatarUrl = asset('storage/' . $sender->avatar);
                            } else {
                                // Initials logic like in members/index.blade.php
                                $name = $sender->name ?? 'User';
                                $words = explode(' ', $name);
                                if (count($words) >= 2) {
                                    $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                                } else {
                                    $initials = strtoupper(substr($name, 0, 2));
                                }
                                
                                $colors = ['#0A5C36', '#2ecc71', '#3498db', '#9b59b6', '#e74c3c', '#f39c12'];
                                $colorIndex = crc32($name) % count($colors);
                                $avatarColor = $colors[$colorIndex];
                            }
                        }
                    }
                }

                return [
                    'id' => $notification->id,
                    'title' => $data['title'] ?? $this->getDefaultTitle($notification->type),
                    'message' => $data['message'] ?? '',
                    'type' => $notification->type,
                    'created_at' => Carbon::parse($notification->created_at)->diffForHumans(),
                    'icon' => $isSystem ? $this->getNotificationIcon($notification->type) : ($data['icon'] ?? $this->getNotificationIcon($notification->type)),
                    'color' => $isSystem ? $this->getNotificationColor($notification->type) : ($data['color'] ?? $this->getNotificationColor($notification->type)),
                    'action_url' => $data['action_url'] ?? null,
                    'action_text' => $data['action_text'] ?? 'Lihat Detail',
                    'avatar_url' => $avatarUrl,
                    'initials' => $initials,
                    'avatar_color' => $avatarColor,
                    'data' => $data
                ];
            })
            ->toArray();

        return response()->json([
            'success' => true,
            'notifications' => $notifications
        ]);
    }

    public function delete(Request $request, $id)
    {
        $user = auth()->user();

        $deleted = DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', 'App\Models\User')
            ->delete();

        if ($deleted) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    }

    public function clearAll()
    {
        $user = auth()->user();

        DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', 'App\Models\User')
            ->delete();

        return response()->json(['success' => true]);
    }

    public function readAndRedirect($id)
    {
        $user = auth()->user();

        $notification = DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', 'App\Models\User')
            ->first();

        if (!$notification) {
            return redirect()->route('buyer.notifications.index')
                ->with('error', 'Notification not found');
        }

        // Mark as read
        DB::table('notifications')
            ->where('id', $id)
            ->update(['read_at' => now()]);

        $data = json_decode($notification->data, true) ?? [];

        // Handle redirection based on notification type
        $actionUrl = $data['action_url'] ?? null;
        
        if ($actionUrl) {
            return redirect($actionUrl);
        }

        // Default redirect based on notification type
        switch ($notification->type) {
            case 'community_removed':
                return redirect()->route('buyer.communities.index')
                    ->with('info', $data['message'] ?? 'Anda telah dikeluarkan dari komunitas');
            
            case 'community_role_changed':
                $communityName = $data['community_name'] ?? 'Komunitas';
                return redirect()->route('buyer.communities.index')
                    ->with('success', "Role Anda di komunitas {$communityName} telah diubah");
            
            case 'community_message':
                $communityName = $data['community_name'] ?? 'Komunitas';
                return redirect()->route('buyer.communities.index')
                    ->with('info', "Ada pesan dari komunitas {$communityName}");
            
            case 'community_invitation':
                $communityName = $data['community_name'] ?? 'Komunitas';
                return redirect()->route('buyer.communities.index')
                    ->with('success', "Undangan untuk bergabung dengan {$communityName}");
            
            case 'booking_confirmed':
                return redirect()->route('buyer.booking.index')
                    ->with('success', 'Booking Anda telah dikonfirmasi');
            
            case 'booking_cancelled':
                return redirect()->route('buyer.booking.index')
                    ->with('warning', 'Booking Anda telah dibatalkan');
            
            case 'main_bareng_invitation':
            case 'main_bareng_invitation_accepted':
            case 'main_bareng_invitation_rejected':
            case 'main_bareng_join_request':
            case 'main_bareng_join_approved':
            case 'main_bareng_join_rejected':
                $playTogetherId = $data['play_together_id'] ?? null;
                if ($playTogetherId) {
                    return redirect()->route('buyer.main_bareng_saya.show', $playTogetherId);
                }
                return redirect()->route('buyer.main_bareng_saya.index');
            
            default:
                return redirect()->route('buyer.notifications.index')
                    ->with('info', 'Notification marked as read');
        }
    }

    public function show($id)
    {
        $user = auth()->user();

        $notification = DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', 'App\Models\User')
            ->first();

        if (!$notification) {
            return redirect()->route('buyer.notifications.index')
                ->with('error', 'Notification not found');
        }

        // Mark as read
        DB::table('notifications')
            ->where('id', $id)
            ->update(['read_at' => now()]);

        $data = json_decode($notification->data, true) ?? [];

        return view('buyer.notifications.show', [
            'notification' => [
                'id' => $notification->id,
                'title' => $data['title'] ?? $this->getDefaultTitle($notification->type),
                'message' => $data['message'] ?? '',
                'type' => $notification->type,
                'created_at' => Carbon::parse($notification->created_at)->format('d M Y H:i'),
                'icon' => $data['icon'] ?? $this->getNotificationIcon($notification->type),
                'color' => $data['color'] ?? $this->getNotificationColor($notification->type),
                'action_url' => $data['action_url'] ?? null,
                'action_text' => $data['action_text'] ?? 'Lihat Detail',
                'data' => $data,
                'priority' => $data['priority'] ?? 'normal'
            ]
        ]);
    }

    public function markAsUnread(Request $request, $id)
    {
        $user = auth()->user();

        $updated = DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', 'App\Models\User')
            ->whereNotNull('read_at')
            ->update(['read_at' => null]);

        if ($updated) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Notification not found or already unread'], 404);
    }
}