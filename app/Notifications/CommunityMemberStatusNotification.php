<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class CommunityMemberStatusNotification extends Notification
{
    use Queueable;

    public $community;
    public $actor; // User who performed the action (manager or inviter)
    public $targetUser; // The user who got accepted/rejected
    public $status; // accepted|rejected

    public function __construct($community, $actor, $targetUser, $status)
    {
        $this->community = $community;
        $this->actor = $actor;
        $this->targetUser = $targetUser;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        $status = $this->status;
        $color = 'primary';
        $notificationType = 'community_update';
        $icon = 'fas fa-bell';

        if ($status === 'accepted') {
            $title = "Permintaan diterima";
            $message = "Kamu sudah diterima di komunitas {$this->community->name}";
            $color = 'success';
            $notificationType = 'community_request_approved';
            $icon = 'fas fa-check-circle';
        } elseif ($status === 'rejected') {
            $title = "Permintaan ditolak";
            $message = "Anda ditolak untuk bergabung di komunitas {$this->community->name}";
            $color = 'danger';
            $notificationType = 'community_request_rejected';
            $icon = 'fas fa-times-circle';
        } elseif ($status === 'community_invitation') {
            $title = "Undangan bergabung";
            $message = "Admin komunitas \"{$this->community->name}\" mengundang kamu untuk bergabung dengan komunitas mereka. Terima?";
            $color = 'info';
            $notificationType = 'community_reinvitation';
            $icon = 'fas fa-envelope';
        } elseif ($status === 'disbanded') {
            $title = "Komunitas Dibubarkan";
            $message = "Komunitas \"{$this->community->name}\" telah dibubarkan oleh pembuatnya.";
            $color = 'danger';
            $notificationType = 'community_disbanded';
            $icon = 'fas fa-trash-alt';
        } elseif ($status === 'left_community') {
            $title = "Anggota Keluar";
            $message = "{$this->actor->name} telah keluar dari komunitas {$this->community->name}.";
            $color = 'warning';
            $notificationType = 'community_member_left';
            $icon = 'fas fa-sign-out-alt';
        } elseif ($status === 'invitation_accepted') {
            $title = "Undangan diterima";
            $message = "Undangan kamu untuk {$this->actor->name} bergabung di komunitas {$this->community->name} telah diterima";
            $color = 'success';
            $notificationType = 'community_invitation_accepted';
            $icon = 'fas fa-check-circle';
        } elseif ($status === 'invitation_rejected') {
            $title = "Undangan ditolak";
            $message = "Undangan kamu untuk {$this->actor->name} bergabung di komunitas {$this->community->name} ditolak";
            $color = 'danger';
            $notificationType = 'community_invitation_rejected';
            $icon = 'fas fa-times-circle';
        } else { // pending
            $title = "Permintaan bergabung";
            $message = "{$this->actor->name} mengajukan permintaan bergabung ke komunitas {$this->community->name}";
            $color = 'info';
            $notificationType = 'community_request_pending';
            $icon = 'fas fa-users';
        }

        return [
            'type' => 'community',
            'notification_type' => $notificationType,
            'title' => $title,
            'message' => $message,
            'community_id' => $this->community->id,
            'community_name' => $this->community->name,
            'actor_id' => $this->actor->id ?? null,
            'actor_name' => $this->actor->name ?? null,
            'target_user_id' => $this->targetUser->id ?? null,
            'target_user_name' => $this->targetUser->name ?? null,
            'status' => $this->status,
            'icon' => $icon,
            'color' => $color,
            'action_url' => $this->status === 'pending' 
                ? route('buyer.communities.invite-anggota', $this->community->id) 
                : ($this->status === 'disbanded' ? route('buyer.communities.index') : route('buyer.communities.show', $this->community->id))
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}