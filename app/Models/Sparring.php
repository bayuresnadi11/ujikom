<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sparring extends Model
{
    use HasFactory;

    protected $table = 'sparring';

    protected $fillable = [
        'name',
        'booking_id',
        'type',                  // free | paid
        'date',
        'location',
        'privacy',               // public | private | community
        'cost_per_participant',
        'host_approval',
        'created_by',
        'description',
        'status',                // pending | active | completed | canceled
        'payment_type',
    ];

    protected $casts = [
        'date' => 'date',
        'host_approval' => 'boolean',
        'cost_per_participant' => 'decimal:2',
    ];

    /* =======================
     |        RELATIONS
     |=======================*/

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(SparringParticipant::class, 'sparring_id');
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(SparringInvitation::class, 'sparring_id');
    }
}
