<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\RoleRequest;
use App\Models\User;

class RoleRequestStatusNotification extends Notification
{
    use Queueable;

    protected $roleRequest;
    protected $status;

    /**
     * Create a new notification instance.
     */
    public function __construct(RoleRequest $roleRequest, $status)
    {
        $this->roleRequest = $roleRequest;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        $status = $this->status;
        $color = 'primary';
        $notificationType = 'role_request_update';
        $icon = 'fas fa-bell';
        $title = '';
        $message = '';

        if ($status === 'approved') {
            $title = "Pengajuan Disetujui";
            $message = "Selamat! Pengajuan Anda untuk menjadi Pemilik Lapangan telah disetujui. Anda sekarang dapat mengelola lapangan Anda.";
            $color = 'success';
            $notificationType = 'role_request_approved';
            $icon = 'fas fa-check-circle';
        } elseif ($status === 'rejected') {
            $title = "Pengajuan Ditolak";
            $message = "Mohon maaf, pengajuan Anda untuk menjadi Pemilik Lapangan ditolak. Silakan hubungi admin untuk informasi lebih lanjut.";
            $color = 'danger';
            $notificationType = 'role_request_rejected';
            $icon = 'fas fa-times-circle';
        }

        return [
            'type' => 'role_request',
            'notification_type' => $notificationType,
            'title' => $title,
            'message' => $message,
            'role_request_id' => $this->roleRequest->id,
            'requested_role' => $this->roleRequest->requested_role,
            'status' => $status,
            'icon' => $icon,
            'color' => $color,
            'action_url' => route('buyer.profile'),
        ];
    }
}
