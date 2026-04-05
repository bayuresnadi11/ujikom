<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'logo',
        'background_image',
        'type',
        'requires_approval',
        'location',
        'created_by',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    // ✅ FIXED: Relasi members - pakai belongsToMany karena many-to-many
    public function members()
    {
        return $this->belongsToMany(User::class, 'community_members', 'community_id', 'user_id')
            ->withPivot('role', 'status', 'joined_at', 'invitation_id')
            ->wherePivot('status', 'active'); // Only get active members
    }

    // ✅ Relasi untuk semua member (termasuk yang removed)
    public function allMembers()
    {
        return $this->belongsToMany(User::class, 'community_members', 'community_id', 'user_id')
            ->withPivot('role', 'status', 'joined_at', 'invitation_id');
    }

    // ✅ Helper untuk mendapatkan CommunityMember model
    public function memberRecords()
    {
        return $this->hasMany(CommunityMember::class);
    }

    public function communityRequests()
    {
        return $this->hasMany(CommunityRequest::class);
    }

    public function invitations()
    {
        return $this->hasMany(CommunityInvitation::class);
    }

    public function playTogetherEvents()
    {
        return $this->hasMany(PlayTogether::class);
    }

    public function sparringParticipants()
    {
        return $this->hasMany(SparringParticipant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    
    public function venue()
    {
        return $this->hasMany(Venue::class, 'created_by', 'id');
    }

    public function requests()
    {
        return $this->hasMany(CommunityRequest::class);
    }

    public function pendingRequests()
    {
        return $this->hasMany(CommunityRequest::class)
            ->where('status', 'pending');
    }

    public function joinRequests()
    {
        return $this->hasMany(CommunityMember::class)
                    ->where('status', 'pending');
    }

    // Accessor for Chat compatibility
    public function getAvatarAttribute()
    {
        return $this->logo;
    }

    /**
     * ✅ Get member count
     */
    public function getMemberCountAttribute()
    {
        return $this->members()->count();
    }

    public function playTogethers()
    {
        return $this->hasMany(PlayTogether::class);
    }

    public function galleries()
    {
        return $this->hasMany(CommunityGallery::class)->latest();
    }

    // ✅ Relasi conversation
    public function conversation()
    {
        return $this->hasOne(Conversation::class);
    }

    // ============================================
    // Helper Methods
    // ============================================

    /**
     * ✅ Cek apakah user adalah member aktif
     */
    public function isMember($userId)
    {
        return $this->members()->where('user_id', $userId)->exists();
    }

    /**
     * ✅ Cek apakah user adalah admin/manager
     */
    public function isManager($userId)
    {
        return $this->memberRecords()
            ->where('user_id', $userId)
            ->where('role', 'admin')
            ->where('status', 'active')
            ->exists();
    }
}