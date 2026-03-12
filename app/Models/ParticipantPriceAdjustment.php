<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ParticipantPriceAdjustment extends Model
{
    use HasFactory;

    protected $table = 'participant_price_adjustments';

    protected $fillable = [
        'source_type',
        'source_id',
        'old_amount',
        'new_amount',
        'reason',
        'effective_at',
        'adjusted_by'
    ];

    protected $casts = [
        'old_amount' => 'decimal:2',
        'new_amount' => 'decimal:2',
        'effective_at' => 'datetime',
    ];

    /* =======================
     |        RELATIONS
     |=======================*/

    /**
     * User yang melakukan adjustment
     */
    public function adjuster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adjusted_by');
    }

    /**
     * Event source (PlayTogether atau Sparring)
     */
    public function source(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get PlayTogether jika source_type adalah play_together
     */
    public function playTogether(): BelongsTo
    {
        return $this->belongsTo(PlayTogether::class, 'source_id')
                    ->where('source_type', 'play_together');
    }

    /**
     * Get Sparring jika source_type adalah sparring
     */
    public function sparring(): BelongsTo
    {
        return $this->belongsTo(Sparring::class, 'source_id')
                    ->where('source_type', 'sparring');
    }

    /* =======================
     |        HELPERS
     |=======================*/

    /**
     * Cek apakah adjustment sudah berlaku
     */
    public function isEffective(): bool
    {
        return $this->effective_at && $this->effective_at->isPast();
    }

    /**
     * Hitung persentase kenaikan/turunan harga
     */
    public function getPercentageChange(): float
    {
        if ($this->old_amount == 0) {
            return 0;
        }
        
        $change = (($this->new_amount - $this->old_amount) / $this->old_amount) * 100;
        return round($change, 2);
    }

    /**
     * Cek apakah harga naik
     */
    public function isPriceIncrease(): bool
    {
        return $this->new_amount > $this->old_amount;
    }

    /**
     * Cek apakah harga turun
     */
    public function isPriceDecrease(): bool
    {
        return $this->new_amount < $this->old_amount;
    }

    /**
     * Get price change amount
     */
    public function getChangeAmount(): float
    {
        return $this->new_amount - $this->old_amount;
    }

    /**
     * Get readable reason
     */
    public function getReasonText(): string
    {
        $reasons = [
            'participant_shortage' => 'Kurangnya peserta',
            'deadline_approaching' => 'Mendekati deadline',
            'special_offer' => 'Penawaran khusus',
            'holiday_season' => 'Musim liburan',
            'weather_condition' => 'Kondisi cuaca',
            'venue_discount' => 'Diskon venue',
            'other' => 'Lainnya'
        ];

        return $reasons[$this->reason] ?? $this->reason ?? 'Tidak ada alasan';
    }

    /**
     * Apply adjustment ke semua participants yang belum bayar
     */
    public function applyToUnpaidParticipants(): int
    {
        $updatedCount = 0;
        
        if ($this->source_type === 'play_together') {
            $playTogether = PlayTogether::find($this->source_id);
            if ($playTogether) {
                $updatedCount = $playTogether->participants()
                    ->where('payment_status', '!=', 'paid')
                    ->update(['amount' => $this->new_amount]);
            }
        } elseif ($this->source_type === 'sparring') {
            // Implement untuk sparring jika diperlukan
        }
        
        return $updatedCount;
    }

    /**
     * Rollback adjustment
     */
    public function rollback(): bool
    {
        if ($this->source_type === 'play_together') {
            $playTogether = PlayTogether::find($this->source_id);
            if ($playTogether) {
                $playTogether->participants()
                    ->where('payment_status', '!=', 'paid')
                    ->update(['amount' => $this->old_amount]);
            }
        }
        
        return $this->delete();
    }
}