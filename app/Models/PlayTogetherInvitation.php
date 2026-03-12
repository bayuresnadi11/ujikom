<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayTogetherInvitation extends Model
{
    use HasFactory;

    protected $table = 'play_together_invitations';

    protected $fillable = [
        'play_together_id',
        'invited_user_id',
        'invited_by',
        'status',
        'responded_at'
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    // Relationships
    public function playTogether()
    {
        return $this->belongsTo(PlayTogether::class, 'play_together_id');
    }

    public function invitedUser()
    {
        return $this->belongsTo(User::class, 'invited_user_id');
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function participant()
    {
        return $this->hasOne(PlayTogetherParticipant::class, 'invitation_id');
    }
}