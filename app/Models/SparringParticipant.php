<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SparringParticipant extends Model
{
    use HasFactory;

    protected $table = 'sparring_participants';

    protected $fillable = [
        'sparring_id',
        'community_id',
        'invited_by',
        'invitation_status',
        'invitation_id',
        'payment_status',
        'amount',
        'payment_token',
        'payment_url',
        'midtrans_order_id',
        'midtrans_transaction_status',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /* =======================
     |        RELATIONS
     |=======================*/

    public function sparring(): BelongsTo
    {
        return $this->belongsTo(Sparring::class, 'sparring_id');
    }

    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class, 'community_id');
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(Community::class, 'invited_by');
    }

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(SparringInvitation::class, 'invitation_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(
            BookingParticipantPayment::class,
            'sparring_participant_id'
        );
    }

    /* =======================
     |        HELPERS
     |=======================*/

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid'
            && in_array($this->midtrans_transaction_status, ['settlement', 'capture']);
    }

    public function markAsPaid(string $transactionStatus = 'settlement'): bool
    {
        $this->payment_status = 'paid';
        $this->midtrans_transaction_status = $transactionStatus;
        $this->paid_at = now();

        return $this->save();
    }
}
