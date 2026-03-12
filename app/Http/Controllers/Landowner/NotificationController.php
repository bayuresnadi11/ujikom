<?php

namespace App\Http\Controllers\Landowner;

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

                return [
                    'id' => $notification->id,
                    'title' => $data['title'] ?? $this->getDefaultTitle($notification->type),
                    'message' => $data['message'] ?? '',
                    'type' => $notification->type,
                    'is_read' => $notification->read_at !== null,
                    'created_at' => Carbon::parse($notification->created_at),
                    'icon' => $data['icon'] ?? $this->getNotificationIcon($notification->type),
                    'source' => 'laravel',
                    'action_url' => $data['action_url'] ?? null,
                    'action_text' => $data['action_text'] ?? 'Lihat Detail',
                    'color' => $data['color'] ?? $this->getNotificationColor($notification->type),
                    'priority' => $data['priority'] ?? 'normal',
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

        return view('landowner.notifications.index', [
            'allNotifications' => $notifications,
            'unreadCount' => $unreadCount
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
            'booking_confirmed' => 'Booking Dikonfirmasi',
            'booking_cancelled' => 'Booking Dibatalkan',
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
            'venue_booking' => 'Booking Venue Baru',
            'venue_payment' => 'Pembayaran Venue',
            'schedule_update' => 'Update Jadwal',
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
            'booking_confirmed' => 'fas fa-calendar-check',
            'booking_cancelled' => 'fas fa-calendar-times',
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
            'venue_booking' => 'fas fa-calendar-plus',
            'venue_payment' => 'fas fa-credit-card',
            'schedule_update' => 'fas fa-calendar-alt',
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
            'booking_confirmed' => 'success',
            'booking_cancelled' => 'warning',
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
            'venue_booking' => 'primary',
            'venue_payment' => 'success',
            'schedule_update' => 'info',
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

    public function show($id)
    {
        $user = auth()->user();

        $notification = DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', 'App\Models\User')
            ->first();

        if (!$notification) {
            return redirect()->route('landowner.notifications.index')
                ->with('error', 'Notification not found');
        }

        // Mark as read
        DB::table('notifications')
            ->where('id', $id)
            ->update(['read_at' => now()]);

        $data = json_decode($notification->data, true) ?? [];

        return view('landowner.notifications.show', [
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
}
