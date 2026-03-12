<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SparringInvitation extends Model
{
    use HasFactory;

    protected $table = 'sparring_invitations';

    protected $fillable = [
        'sparring_id',
        'invited_by',
        'invited_community_id',
        'status',
        'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    public function sparring(): BelongsTo
    {
        return $this->belongsTo(Sparring::class, 'sparring_id');
    }

    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(Community::class, 'invited_by');
    }

    public function invitedCommunity(): BelongsTo
    {
        return $this->belongsTo(Community::class, 'invited_community_id');
    }
}
