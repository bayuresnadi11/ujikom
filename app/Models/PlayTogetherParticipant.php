<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlayTogetherParticipant extends Model
{
    use HasFactory;

    protected $table = 'play_together_participants';

    protected $fillable = [
        'play_together_id',
        'user_id',
        'invited_by',
        'invitation_status',
        'invitation_id',
        'approval_status',
        'payment_status',
        'amount',
        'total_paid',
        'payment_token',
        'payment_url',
        'midtrans_order_id',
        'midtrans_transaction_status',
        'paid_at',
        'joined_at'
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
        'total_paid' => 'decimal:2',
    ];

    /* =======================
     |        RELATIONS
     |=======================*/

    /**
     * Play Together event
     */
    public function playTogether(): BelongsTo
    {
        return $this->belongsTo(PlayTogether::class, 'play_together_id');
    }

    /**
     * User participant
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * User yang mengundang
     */
    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Invitation terkait
     */
    public function invitation(): BelongsTo
    {
        return $this->belongsTo(PlayTogetherInvitation::class, 'invitation_id');
    }

    /**
     * Pembayaran participant
     */
    public function payments(): HasMany
    {
        return $this->hasMany(BookingParticipantPayment::class, 'play_together_participant_id');
    }

    /* =======================
     |        SCOPES
     |=======================*/

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePendingPayment($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /* =======================
     |        HELPERS
     |=======================*/

    /**
     * Cek apakah participant sudah approved
     */
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Cek apakah participant sudah bayar
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid' && 
               in_array($this->midtrans_transaction_status, ['settlement', 'capture']);
    }

    /**
     * Cek apakah participant pending payment
     */
    public function isPendingPayment(): bool
    {
        return $this->payment_status === 'pending' || 
               $this->midtrans_transaction_status === 'pending';
    }

    /**
     * Cek apakah participant sudah bergabung
     */
    public function hasJoined(): bool
    {
        return !empty($this->joined_at);
    }

    /**
     * Approve participant
     */
    public function approve(): bool
    {
        $this->approval_status = 'approved';
        $this->joined_at = now();
        return $this->save();
    }

    /**
     * Reject participant
     */
    public function reject(): bool
    {
        $this->approval_status = 'rejected';
        return $this->save();
    }

    /**
     * Mark as paid
     */
    public function markAsPaid(string $transactionStatus = 'settlement'): bool
    {
        $this->payment_status = 'paid';
        $this->midtrans_transaction_status = $transactionStatus;
        $this->paid_at = now();
        $this->total_paid = $this->amount;
        
        if ($this->save()) {
            // Update total paid di play together melalui booking
            if ($this->playTogether && $this->playTogether->booking) {
                $this->playTogether->booking->addParticipantPayment((float)$this->amount);
            }
            
            return true;
        }
        
        return false;
    }

    /**
     * Get remaining payment amount
     */
    public function getRemainingAmount(): float
    {
        return max(0, (float)$this->amount - (float)$this->total_paid);
    }
    /**
 * Get unpaid amount for this participant
 */
public function getUnpaidAmount(): float
{
    return max(0, (float)$this->amount - (float)$this->total_paid);
}

/**
 * Check if participant is fully paid
 */
public function isFullyPaid(): bool
{
    return $this->payment_status === 'paid' && 
           (float)$this->total_paid >= (float)$this->amount;
}
}