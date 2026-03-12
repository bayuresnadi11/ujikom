<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot the model and add listeners.
     */
    protected static function booted()
    {
        static::created(function ($notification) {
            // Log detail untuk debugging
            Log::info('Notification Created Hook triggered', [
                'notification_id' => $notification->id,
                'notifiable_type' => $notification->notifiable_type,
                'notifiable_id' => $notification->notifiable_id,
            ]);

            // Hanya kirim WA jika notifiable adalah User
            if ($notification->notifiable_type === 'App\Models\User') {
                $user = $notification->notifiable;
                
                if ($user) {
                    Log::info('Notifiable User found', [
                        'user_id' => $user->id,
                        'user_phone' => $user->phone
                    ]);

                    // Cek apakah user punya nomor telepon
                    if ($user->phone) {
                        $waMessage = self::formatWaMessage($notification);
                        
                        // Gunakan fungsi global kirimWa
                        if (function_exists('kirimWa')) {
                            kirimWa($user->phone, $waMessage);
                        }
                    } else {
                        Log::warning('User does not have a phone number', ['user_id' => $user->id]);
                    }
                } else {
                    Log::error('Notifiable User NOT found for notification', [
                        'notifiable_id' => $notification->notifiable_id
                    ]);
                }
            }
        });
    }

    /**
     * Format pesan WhatsApp agar terlihat lebih premium dan informatif
     */
    protected static function formatWaMessage($notification)
    {
        $data = $notification->data;
        $type = $notification->type;

        $title = $data['title'] ?? 'Notifikasi';
        $message = strip_tags($data['message'] ?? '');

        $waMessage  = "*{$title}*\n\n";
        $waMessage .= "{$message}\n\n";

        // Informasi tambahan
        $waMessage .= self::getAdditionalInfo($type, $data);

        // Link action jika ada
        if (!empty($data['action_url'])) {
            $actionUrl = $data['action_url'];

            $localUrls = ['http://127.0.0.1:8000', 'http://localhost:8000', 'http://localhost'];
            $productionUrl = 'https://sewalap.kakara.my.id';
            $actionUrl = str_replace($localUrls, $productionUrl, $actionUrl);

            $waMessage .= "Detail:\n";
            $waMessage .= $actionUrl . "\n\n";
        }

        $waMessage .= "Pesan ini dikirim otomatis oleh sistem.\n";
        $waMessage .= "Tim SewaLapangan";

        return $waMessage;
    }
    
    /**
     * Dapatkan informasi tambahan berdasarkan tipe
     */
    protected static function getAdditionalInfo($type, $data)
    {
        $info = "";

        switch ($type) {
            case 'community_role_changed':
                if (!empty($data['community_name'])) {
                    $info .= "Komunitas: {$data['community_name']}\n";
                }
                if (isset($data['old_role'], $data['new_role'])) {
                    $oldRole = ucfirst($data['old_role']);
                    $newRole = ucfirst($data['new_role']);
                    $info .= "Perubahan Role: {$oldRole} -> {$newRole}\n\n";
                }
                break;

            case 'community_removed':
                if (!empty($data['community_name'])) {
                    $info .= "Komunitas: {$data['community_name']}\n";
                }
                if (!empty($data['reason'])) {
                    $info .= "Alasan: {$data['reason']}\n";
                }
                $info .= "\n";
                break;

            case 'community_message':
                if (!empty($data['community_name'])) {
                    $info .= "Komunitas: {$data['community_name']}\n";
                }
                if (!empty($data['message_type'])) {
                    $typeText = $data['message_type'] === 'announcement' ? 'Pengumuman' : 'Pengingat';
                    $info .= "Jenis: {$typeText}\n";
                }
                $info .= "\n";
                break;

            case 'main_bareng_invitation':
                if (!empty($data['play_together_id'])) {
                    $info .= "ID Main Bareng: #{$data['play_together_id']}\n";
                }
                if (!empty($data['message']) &&
                    preg_match('/main bareng di (.+?)\.?$/', strip_tags($data['message']), $matches)
                ) {
                    $info .= "Lokasi: {$matches[1]}\n";
                }
                $info .= "\n";
                break;

            case 'booking_confirmed':
            case 'booking_cancelled':
                if (!empty($data['booking_id'])) {
                    $info .= "ID Booking: #{$data['booking_id']}\n";
                }
                if (!empty($data['field_name'])) {
                    $info .= "Lapangan: {$data['field_name']}\n";
                }
                if (!empty($data['booking_date'])) {
                    $info .= "Tanggal: {$data['booking_date']}\n";
                }
                if (!empty($data['booking_time'])) {
                    $info .= "Waktu: {$data['booking_time']}\n";
                }
                $info .= "\n";
                break;

            case 'payment_success':
            case 'payment_failed':
                if (!empty($data['booking_id'])) {
                    $info .= "ID Booking: #{$data['booking_id']}\n";
                }
                if (!empty($data['amount'])) {
                    $info .= "Jumlah: Rp " . number_format($data['amount'], 0, ',', '.') . "\n";
                }
                if (!empty($data['payment_method'])) {
                    $info .= "Metode: {$data['payment_method']}\n";
                }
                $info .= "\n";
                break;
        }

        return $info;
    }


    // ================= RELATIONSHIPS =================
    
    /**
     * Get the owning notifiable model.
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user that owns the notification (for backward compatibility)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'notifiable_id');
    }

    // ================= SCOPES =================
    
    /**
     * Scope untuk notifikasi yang belum dibaca
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope untuk notifikasi yang sudah dibaca
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope untuk notifikasi berdasarkan tipe
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope untuk notifikasi terbaru
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // ================= METHODS =================
    
    /**
     * Tandai notifikasi sebagai sudah dibaca
     */
    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Tandai notifikasi sebagai belum dibaca
     */
    public function markAsUnread()
    {
        if (!is_null($this->read_at)) {
            $this->update(['read_at' => null]);
        }
    }

    /**
     * Cek apakah notifikasi sudah dibaca
     */
    public function isRead()
    {
        return !is_null($this->read_at);
    }

    /**
     * Cek apakah notifikasi belum dibaca
     */
    public function isUnread()
    {
        return is_null($this->read_at);
    }

    // ================= STATIC METHODS =================
    
    /**
     * Kirim notifikasi untuk member yang dikeluarkan
     */
    public static function sendMemberRemovedNotification($userId, $communityName, $message, $reason = null, $senderId = null, $communityId = null)
    {
        return self::create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'type' => 'community_removed',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $userId,
            'data' => [
                'title' => 'Anda Telah Dikeluarkan dari Komunitas',
                'message' => $message,
                'community_name' => $communityName,
                'community_id' => $communityId,
                'reason' => $reason,
                'sender_id' => $senderId,
                'icon' => 'user-slash',
                'color' => 'danger',
                'priority' => 'high',
            ],
            'read_at' => null,
        ]);
    }

    /**
     * Kirim notifikasi untuk perubahan role
     */
    public static function sendRoleChangedNotification($userId, $communityName, $newRole, $oldRole, $senderId = null, $communityId = null)
    {
        Log::info('sendRoleChangedNotification called', [
            'userId' => $userId,
            'communityName' => $communityName,
            'newRole' => $newRole
        ]);

        $roleText = $newRole === 'admin' ? 'Admin' : 'Anggota';
        
        return self::create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'type' => 'community_role_changed',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $userId,
            'data' => [
                'title' => 'Role Anda Diubah',
                'message' => "Role Anda di komunitas <strong>{$communityName}</strong> telah diubah dari <strong>{$oldRole}</strong> menjadi <strong>{$roleText}</strong>.",
                'community_name' => $communityName,
                'new_role' => $newRole,
                'old_role' => $oldRole,
                'sender_id' => $senderId,
                'community_id' => $communityId,
                'icon' => 'fas fa-user-shield',
                'color' => 'warning'
            ]
        ]);
    }

    /**
     * Kirim notifikasi pesan komunitas
     */
    public static function sendCommunityMessageNotification($userId, $communityName, $message, $messageType = 'announcement', $senderId = null, $communityId = null)
    {
        $title = $messageType === 'announcement' ? 'Pengumuman Komunitas' : 'Pengingat dari Komunitas';
        
        return self::create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'type' => 'community_message',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $userId,
            'data' => [
                'title' => $title,
                'message' => $message,
                'community_name' => $communityName,
                'community_id' => $communityId,
                'message_type' => $messageType,
                'sender_id' => $senderId,
                'icon' => $messageType === 'announcement' ? 'bullhorn' : 'clock',
                'color' => 'info',
                'priority' => 'normal',
            ],
            'read_at' => null,
        ]);
    }

    /**
     * Tandai semua notifikasi user sebagai sudah dibaca
     */
    public static function markAllAsReadForUser($userId)
    {
        return self::where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Get jumlah notifikasi belum dibaca untuk user
     */
    public static function getUnreadCountForUser($userId)
    {
        return self::where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', $userId)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Kirim notifikasi undangan Main Bareng
     */
    public static function sendMainBarengInvitationNotification($userId, $hostName, $location, $playTogetherId, $invitationId)
    {
        return self::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'type' => 'main_bareng_invitation',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $userId,
            'data' => [
                'title' => 'Undangan Main Bareng',
                'message' => "{$hostName} mengundang Anda untuk main bareng di {$location}.",
                'play_together_id' => $playTogetherId,
                'invitation_id' => $invitationId,
                'icon' => 'futbol',
                'color' => 'success',
                'priority' => 'high',
                'action_url' => route('buyer.main_bareng_saya.show', $playTogetherId),
            ],
            'read_at' => null,
        ]);
    }

    /**
     * Kirim notifikasi balasan undangan Main Bareng (untuk Host)
     */
    public static function sendMainBarengInvitationResponseNotification($hostId, $userName, $status, $location, $playTogetherId)
    {
        $statusText = $status === 'accepted' ? 'menerima' : 'menolak';
        $color = $status === 'accepted' ? 'success' : 'danger';
        $icon = $status === 'accepted' ? 'check-circle' : 'times-circle';

        return self::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'type' => $status === 'accepted' ? 'main_bareng_invitation_accepted' : 'main_bareng_invitation_rejected',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $hostId,
            'data' => [
                'title' => "Undangan " . ($status === 'accepted' ? 'Diterima' : 'Ditolak'),
                'message' => "{$userName} telah {$statusText} undangan main bareng Anda di {$location}.",
                'play_together_id' => $playTogetherId,
                'icon' => $icon,
                'color' => $color,
                'priority' => 'normal',
                'action_url' => route('buyer.main_bareng_saya.show', $playTogetherId),
            ],
            'read_at' => null,
        ]);
    }

    /**
     * Kirim notifikasi permintaan bergabung ke Host
     */
    public static function sendMainBarengJoinRequestNotification($hostId, $userName, $location, $playTogetherId, $participantId, $senderId)
    {
        return self::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'type' => 'main_bareng_join_request',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $hostId,
            'data' => [
                'title' => 'Permintaan Bergabung',
                'message' => "{$userName} mengajukan bergabung ke main bareng Anda di {$location}.",
                'play_together_id' => $playTogetherId,
                'participant_id' => $participantId,
                'user_name' => $userName,
                'sender_id' => $senderId,
                'icon' => 'user-plus',
                'color' => 'info',
                'priority' => 'high',
                'action_url' => route('buyer.main_bareng_saya.show', $playTogetherId),
            ],
            'read_at' => null,
        ]);
    }

    /**
     * Kirim notifikasi hasil keputusan bergabung ke User
     */
    public static function sendMainBarengJoinResponseNotification($userId, $status, $location, $playTogetherId, $senderId)
    {
        $statusText = $status === 'approved' ? 'diterima' : 'ditolak';
        $color = $status === 'approved' ? 'success' : 'danger';
        $icon = $status === 'approved' ? 'check-circle' : 'times-circle';

        return self::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'type' => $status === 'approved' ? 'main_bareng_join_approved' : 'main_bareng_join_rejected',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $userId,
            'data' => [
                'title' => 'Permintaan Bergabung ' . ($status === 'approved' ? 'Diterima' : 'Ditolak'),
                'message' => "Permintaan bergabung Anda di {$location} telah {$statusText}.",
                'play_together_id' => $playTogetherId,
                'sender_id' => $senderId,
                'icon' => $icon,
                'color' => $color,
                'priority' => 'normal',
                'action_url' => route('buyer.main_bareng_saya.show', $playTogetherId),
            ],
            'read_at' => null,
        ]);
    }

    /**
     * Kirim notifikasi pembayaran berhasil ke User
     */
    public static function sendPaymentSuccessNotification($userId, $amount, $bookingId, $playTogetherId = null)
    {
        return self::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'type' => 'payment_success',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $userId,
            'data' => [
                'title' => 'Pembayaran Berhasil',
                'message' => "Pembayaran sebesar Rp " . number_format($amount, 0, ',', '.') . " berhasil diterima.",
                'amount' => $amount,
                'booking_id' => $bookingId,
                'play_together_id' => $playTogetherId,
                'icon' => 'fas fa-money-bill-wave',
                'color' => 'success',
                'priority' => 'high',
                // Jika ada playTogetherId arahkan ke sana, jika tidak ke booking detail (logic bisa disesuaikan)
                'action_url' => $playTogetherId ? route('buyer.main_bareng_saya.show', $playTogetherId) : '#',
            ],
            'read_at' => null,
        ]);
    }

    /**
     * Kirim notifikasi permintaan bergabung KHUSUS SUDAH BAYAR ke Host
     */
    public static function sendMainBarengJoinRequestPaidNotification($hostId, $userName, $location, $playTogetherId, $participantId, $senderId)
    {
        return self::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'type' => 'main_bareng_join_request_paid', // Tipe khusus
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $hostId,
            'data' => [
                'title' => 'Peserta Baru (Sudah Bayar)',
                'message' => "{$userName} telah melakukan pembayaran dan bergabung di main bareng {$location}.",
                'play_together_id' => $playTogetherId,
                'participant_id' => $participantId,
                'user_name' => $userName,
                'sender_id' => $senderId,
                'icon' => 'money-check-alt',
                'color' => 'success',
                'priority' => 'high',
                'action_url' => route('buyer.main_bareng_saya.show', $playTogetherId),
            ],
            'read_at' => null,
        ]);
    }
}
