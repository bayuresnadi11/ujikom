<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Facades\DB;
class PlayTogether extends Model
{
    use HasFactory;

    protected $table = 'play_together';

    protected $fillable = [
        'booking_id',
        'name',
        'community_id',
        'date',
        'location',
        'max_participants',
        'type',
        'payment_deadline',
        'price_per_person',
        'privacy',
        'gender',
        'host_approval',
        'created_by',
        'description',
        'status',
        'payment_type',
    ];

    protected $casts = [
        'date' => 'date',
        'payment_deadline' => 'datetime',
        'host_approval' => 'boolean',
        'price_per_person' => 'decimal:2',
    ];

    /* =======================
     |        RELATIONS
     |=======================*/

    /**
     * Booking terkait
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id')->withDefault();
    }     

    /**
     * Komunitas (opsional)
     */
    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class, 'community_id');
    }

    /**
     * User pembuat / host
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Peserta Main Bareng
     */
    public function participants(): HasMany
    {
        return $this->hasMany(PlayTogetherParticipant::class, 'play_together_id');
    }

    /**
     * Undangan Main Bareng
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(PlayTogetherInvitation::class, 'play_together_id');
    }

    /**
     * Venue melalui booking
     */
    public function venue(): HasOneThrough
    {
        return $this->hasOneThrough(
            Venue::class,
            Booking::class,
            'id',         // FK di bookings (bookings.id)
            'id',         // PK di venues (venues.id)
            'booking_id', // FK di play_together
            'venue_id'    // FK di bookings
        );
    }

    /**
     * Category melalui venue
     */
    public function category(): HasOneThrough
    {
        return $this->hasOneThrough(
            Category::class,
            Venue::class,
            'id',          // FK di venues (venues.id)
            'id',          // PK di categories (categories.id)
            'venue_id',    // FK di play_together (lewat relasi venue)
            'category_id'  // FK di venues
        );
    }

    /**
     * Price adjustments untuk event ini
     */
    public function priceAdjustments(): HasMany
    {
        return $this->hasMany(ParticipantPriceAdjustment::class, 'source_id')
                    ->where('source_type', 'play_together');
    }

    /* =======================
     |        HELPERS
     |=======================*/

    /**
     * Jumlah peserta saat ini yang approved
     */
    public function participantsCount(): int
    {
        return $this->participants()->where('approval_status', 'approved')->count();
    }

    /**
     * Jumlah peserta yang sudah bayar
     */
    public function paidParticipantsCount(): int
    {
        return $this->participants()->where('payment_status', 'paid')->count();
    }

    /**
     * Total uang yang sudah terkumpul dari participants
     */
    public function totalCollectedAmount(): float
    {
        return (float)$this->participants()->where('payment_status', 'paid')->sum('total_paid');
    }

    /**
     * Apakah masih tersedia slot
     */
    public function hasAvailableSlot(): bool
    {
        return $this->participantsCount() < $this->max_participants;
    }

    /**
     * Cek apakah user adalah host
     */
    public function isHost($userId): bool
    {
        return $this->created_by == $userId;
    }

    /**
     * Cek apakah user sudah bergabung
     */
    public function hasJoined($userId): bool
    {
        return $this->participants()
            ->where('user_id', $userId)
            ->where('approval_status', '!=', 'rejected')
            ->exists();
    }

    /**
     * Cek apakah pembayaran participant sudah deadline
     */
    public function isPaymentDeadlinePassed(): bool
    {
        return $this->payment_deadline && $this->payment_deadline->isPast();
    }

    /**
     * Cek apakah bisa mengundang user
     */
    public function canInviteUsers(): bool
    {
        // Cek apakah privacy public atau private (boleh invite user dari mana saja)
        return in_array($this->privacy, ['public', 'private']);
    }

    /**
     * Cek apakah bisa mengundang dari komunitas
     */
    public function canInviteFromCommunity(): bool
    {
        // Cek apakah privacy community (hanya bisa invite dari community)
        return $this->privacy === 'community';
    }

    /**
     * Cek apakah sudah pernah melakukan penyesuaian biaya
     */
    public function hasAdjustedPrice(): bool
    {
        return $this->priceAdjustments()->exists();
    }

    /**
     * Cek apakah bisa melakukan penyesuaian biaya
     */
    public function canAdjustPrice(): bool
    {
        // Cek kriteria untuk penyesuaian biaya
        $isCriticalTime = $this->date->isToday() || $this->date->isTomorrow();
        
        return $this->type === 'paid' && 
               $this->participantsCount() < $this->max_participants && 
               $isCriticalTime && 
               in_array($this->status, ['pending', 'active']) &&
               !$this->hasAdjustedPrice();
    }

    /**
     * Get current effective price per person
     */
    public function getCurrentPrice(): float
    {
        $latestAdjustment = $this->priceAdjustments()
            ->latest('effective_at')
            ->first();
            
        return $latestAdjustment ? (float)$latestAdjustment->new_amount : (float)$this->price_per_person;
    }

    /**
     * Update semua participants dengan harga baru
     */
    public function updateParticipantsPrice(float $newPrice): void
    {
        $this->participants()
            ->where('payment_status', '!=', 'paid')
            ->update(['amount' => $newPrice]);
    }
    /**
 * Get total unpaid amount by participants
 */
public function getTotalUnpaidAmount(): float
{
    return (float)$this->participants()
        ->where('approval_status', 'approved')
        ->where(function ($query) {
            $query->where('payment_status', 'pending')
                ->orWhere('payment_status', 'partial')
                ->orWhere(function ($q) {
                    $q->where('payment_status', 'paid')
                        ->whereColumn('total_paid', '<', 'amount');
                });
        })
        ->sum(DB::raw('COALESCE(amount, 0) - COALESCE(total_paid, 0)'));
}

/**
 * Check if user has unpaid amount for this play together
 */
public function getUserUnpaidAmount($userId): float
{
    $participant = $this->participants()
        ->where('user_id', $userId)
        ->where('approval_status', 'approved')
        ->first();
    
    if (!$participant) {
        return 0;
    }
    
    return max(0, (float)$participant->amount - (float)$participant->total_paid);
}
}