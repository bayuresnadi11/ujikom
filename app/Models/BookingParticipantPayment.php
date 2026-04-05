<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingParticipantPayment extends Model
{
    use HasFactory;

    protected $table = 'booking_participant_payments';

    protected $fillable = [
        'booking_id',
        'user_id',
        'play_together_participant_id',
        'sparring_participant_id',
        'amount',
        'payment_token',
        'payment_url',
        'midtrans_order_id',
        'midtrans_transaction_status',
        'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /* =======================
     |        RELATIONS
     |=======================*/

    /**
     * Booking terkait
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    /**
     * User yang membayar
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Participant Play Together (jika ada)
     */
    public function playTogetherParticipant(): BelongsTo
    {
        return $this->belongsTo(PlayTogetherParticipant::class, 'play_together_participant_id');
    }

    /**
     * Participant Sparring (jika ada)
     */
    public function sparringParticipant(): BelongsTo
    {
        return $this->belongsTo(SparringParticipant::class, 'sparring_participant_id');
    }

    /* =======================
     |        HELPERS
     |=======================*/

    /**
     * Cek apakah pembayaran sudah sukses
     */
    public function isPaid(): bool
    {
        return !empty($this->paid_at) && 
               in_array($this->midtrans_transaction_status, ['settlement', 'capture']);
    }

    /**
     * Cek apakah pembayaran pending
     */
    public function isPending(): bool
    {
        return empty($this->paid_at) || 
               $this->midtrans_transaction_status === 'pending';
    }

    /**
     * Cek apakah pembayaran expired/cancelled
     */
    public function isExpiredOrCancelled(): bool
    {
        return in_array($this->midtrans_transaction_status, ['expire', 'cancel', 'deny']);
    }

    /**
     * Mark payment as paid
     */
    public function markAsPaid(string $transactionStatus = 'settlement'): bool
    {
        $this->midtrans_transaction_status = $transactionStatus;
        $this->paid_at = now();
        
        if ($this->save()) {
            // Update total paid di booking
            $this->booking->addParticipantPayment((float)$this->amount);
            
            // Update status participant jika ada
            if ($this->playTogetherParticipant) {
                $this->playTogetherParticipant->update([
                    'payment_status' => 'paid',
                    'midtrans_transaction_status' => $transactionStatus,
                    'paid_at' => now(),
                    'total_paid' => $this->amount
                ]);
            }
            
            if ($this->sparringParticipant) {
                $this->sparringParticipant->update([
                    'payment_status' => 'paid',
                    'midtrans_transaction_status' => $transactionStatus,
                    'paid_at' => now(),
                    'total_paid' => $this->amount
                ]);
            }
            
            return true;
        }
        
        return false;
    }

    /**
     * Get payment source type
     */
    public function getSourceType(): string
    {
        if ($this->play_together_participant_id) {
            return 'play_together';
        } elseif ($this->sparring_participant_id) {
            return 'sparring';
        }
        
        return 'unknown';
    }

    /**
     * Get payment source ID
     */
    public function getSourceId(): ?int
    {
        if ($this->play_together_participant_id) {
            return $this->play_together_participant_id;
        } elseif ($this->sparring_participant_id) {
            return $this->sparring_participant_id;
        }
        
        return null;
    }
}