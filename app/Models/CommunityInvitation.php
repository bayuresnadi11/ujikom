<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'community_id',
        'invited_user_id',
        'invited_by',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    /**
     * Relasi ke Community
     */
    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    /**
     * Relasi ke User yang diundang
     */
    public function invitedUser()
    {
        return $this->belongsTo(User::class, 'invited_user_id');
    }

    /**
     * Relasi ke User yang mengundang
     */
    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Relasi ke CommunityMember (jika invitation diterima)
     */
    public function communityMember()
    {
        return $this->hasOne(CommunityMember::class, 'invitation_id');
    }

    /**
     * Scope untuk invitations yang masih pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk invitations yang diterima
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope untuk invitations dari komunitas tertentu
     */
    public function scopeForCommunity($query, $communityId)
    {
        return $query->where('community_id', $communityId);
    }

    /**
     * Cek apakah invitation masih valid (pending)
     */
    public function isValid()
    {
        return $this->status === 'pending';
    }

    /**
     * Accept invitation ini
     */
    public function accept()
    {
        $this->update(['status' => 'accepted']);
        return $this;
    }

    /**
     * Reject invitation ini
     */
    public function reject()
    {
        $this->update(['status' => 'rejected']);
        return $this;
    }
}