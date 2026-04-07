<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'user_id',
        'venue_id',
        'schedule_id',
        'ticket_code',
        'scan_status',
        'scan_time',
        'amount',
        'total_paid',
        'paid_amount',
        'booking_payment',
        'pay_by',
        'method',       // Missing field
        'change',       // Missing field
        'midtrans_order_id', // Missing field
        'paid_at',
        'payment_expired_at',
        'community'
    ];

    protected $casts = [
        'scan_time' => 'datetime',
        'paid_at' => 'datetime',
        'payment_expired_at' => 'datetime',
        'amount' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    /* =======================
     |        RELATIONS
     |=======================*/

    /**
     * User pemilik booking
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Venue yang dibooking
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Jadwal venue
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(VenueSchedule::class, 'schedule_id');
    }

    /**
     * Relasi ke Play Together
     */
    public function playTogether(): HasOne
    {
        return $this->hasOne(PlayTogether::class, 'booking_id');
    }
    
    /**
     * Relasi ke Sparring
     */
    public function sparring(): HasOne
    {
        return $this->hasOne(Sparring::class, 'booking_id');
    }

    /**
     * Pembayaran participant untuk booking ini
     */
    public function participantPayments(): HasMany
    {
        return $this->hasMany(BookingParticipantPayment::class, 'booking_id');
    }

    /**
     * Relasi ke Deposit
     */
    public function deposits()
    {
        return $this->hasMany(Deposit::class, 'booking_id');
    }

    /**
     * Relasi ke deposit negatif (saat booking dibuat)
     */
    public function negativeDeposit()
    {
        return $this->hasOne(Deposit::class, 'booking_id')
                    ->where('source_type', 'booking')
                    ->where('amount', '<', 0);
    }

    /**
     * Relasi ke deposit positif (saat booking dibayar)
     */
    public function positiveDeposit()
    {
        return $this->hasOne(Deposit::class, 'booking_id')
                    ->where('source_type', 'booking')
                    ->where('amount', '>', 0);
    }

    /* =======================
     |        HELPERS
     |=======================*/

    /**
     * Cek apakah booking sudah dibayar penuh
     */
    public function isPaid(): bool
    {
        return $this->booking_payment === 'full';
    }
    
    /**
     * Cek apakah booking pending payment
     */
    public function isPendingPayment(): bool
    {
        return $this->booking_payment === 'pending';
    }

    /**
     * Cek apakah booking partial payment
     */
    public function isPartialPayment(): bool
    {
        return $this->booking_payment === 'partial';
    }

    /**
     * Cek apakah pembayaran sudah expired
     */
    public function isPaymentExpired(): bool
    {
        return $this->payment_expired_at && $this->payment_expired_at->isPast();
    }

    /**
     * Cek apakah booking expired atau cancelled
     */
    public function isExpiredOrCancelled(): bool
    {
        return $this->isPaymentExpired();
    }

    /**
     * Cek apakah booking sudah discan
     */
    public function isScanned(): bool
    {
        return $this->scan_status === true;
    }

    /**
     * Hitung sisa pembayaran yang harus dibayar
     */
    public function getRemainingAmount(): float
    {
        if ($this->pay_by === 'host') {
            // Host bayar semua
            return max(0, (float)$this->amount - (float)$this->paid_amount);
        } else {
            // Participants bayar
            return max(0, (float)$this->amount - (float)$this->total_paid);
        }
    }

    /**
     * Cek apakah pembayaran sudah selesai
     */
    public function isPaymentComplete(): bool
    {
        if ($this->pay_by === 'host') {
            return (float)$this->paid_amount >= (float)$this->amount;
        } else {
            return (float)$this->total_paid >= (float)$this->amount;
        }
    }

    /**
     * Update status pembayaran berdasarkan jumlah yang sudah dibayar
     */
    public function updatePaymentStatus(): void
    {
        if ($this->isPaymentComplete()) {
            $this->booking_payment = 'full';
            $this->paid_at = now();
            
            // ============================================
            // OTOMATIS UPDATE DEPOSIT KETIKA LUNAS
            // ============================================
            $this->updateDepositOnPaymentComplete();
        } elseif ($this->total_paid > 0 || $this->paid_amount > 0) {
            $this->booking_payment = 'partial';
        } else {
            $this->booking_payment = 'pending';
        }
        
        $this->save();
    }

    /**
     * Update deposit ketika pembayaran booking selesai
     */
    private function updateDepositOnPaymentComplete(): void
    {
        // Update deposit negatif menjadi completed
        $negativeDeposit = Deposit::where('booking_id', $this->id)
            ->where('source_type', 'booking')
            ->where('amount', '<', 0)
            ->first();
        
        if ($negativeDeposit) {
            $negativeDeposit->update([
                'status' => 'completed',
                'description' => 'Booking lunas - negative deposit completed',
            ]);
        }

        // Buat deposit positif baru
        Deposit::create([
            'user_id' => $this->user_id,
            'booking_id' => $this->id,
            'amount' => $this->amount,
            'source_type' => 'booking',
            'source_id' => $this->id,
            'status' => 'completed',
            'description' => 'Booking lunas - positive deposit created',
        ]);
    }

    /**
     * Set pembayaran dari host
     */
    public function setHostPayment(float $amount): void
    {
        $this->paid_amount = $amount;
        $this->pay_by = 'host';
        $this->updatePaymentStatus();
    }

    /**
     * Generate ticket code unik
     */
    public static function generateTicketCode(): string
    {
        $prefix = 'BK';
        $date = date('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        
        return $prefix . $date . $random;
    }

    /**
     * Scan ticket
     */
    public function scan(): bool
    {
        if ($this->isScanned()) {
            return false; // Sudah discan
        }

        $this->scan_status = true;
        $this->scan_time = now();
        return $this->save();
    }

    public function getChangeAttribute(): ?float
    {
        if ($this->paid_amount === null) {
            return null;
        }

        return (float) $this->paid_amount - (float) $this->amount;
    }

    /**
     * Get unpaid amount for this booking
     */
    public function getUnpaidAmount(): float
    {
        if ($this->booking_payment === 'full') {
            return 0;
        }
        
        $totalAmount = (float)$this->amount;
        $totalPaid = 0;
        
        if ($this->pay_by === 'participant') {
            $totalPaid = (float)$this->total_paid;
        } elseif ($this->pay_by === 'host') {
            $totalPaid = (float)$this->paid_amount;
        }
        
        return max(0, $totalAmount - $totalPaid);
    }

    /**
     * Add participant payment
     */
    public function addParticipantPayment(float $amount): bool
    {
        $this->total_paid = ((float)$this->total_paid) + $amount;
        
        // Update booking_payment status
        if ($this->total_paid >= (float)$this->amount) {
            $this->booking_payment = 'full';
            $this->paid_at = now();
            
            // Trigger deposit update
            $this->updateDepositOnPaymentComplete();
        } elseif ($this->total_paid > 0) {
            $this->booking_payment = 'partial';
        }
        
        return $this->save();
    }

    /**
     * Check if booking has negative deposit
     */
    public function hasNegativeDeposit(): bool
    {
        return $this->deposits()
            ->where('amount', '<', 0)
            ->exists();
    }

    /**
     * Check if booking has positive deposit
     */
    public function hasPositiveDeposit(): bool
    {
        return $this->deposits()
            ->where('amount', '>', 0)
            ->exists();
    }

    /**
     * Get booking deposit status
     */
    public function getDepositStatus(): array
    {
        $negative = $this->deposits()
            ->where('amount', '<', 0)
            ->first();
        
        $positive = $this->deposits()
            ->where('amount', '>', 0)
            ->first();
        
        return [
            'negative_deposit' => $negative,
            'positive_deposit' => $positive,
            'has_negative' => !is_null($negative),
            'has_positive' => !is_null($positive),
            'is_deposit_complete' => !is_null($negative) && !is_null($positive) && 
                                    $negative->status === 'completed' && 
                                    $positive->status === 'completed',
        ];
    }

    /**
     * Get net deposit amount for this booking
     */
    public function getNetDepositAmount(): float
    {
        $deposits = $this->deposits()
            ->where('source_type', 'booking')
            ->where('status', 'completed')
            ->get();
        
        return $deposits->sum('amount');
    }

    /**
     * Get deposit creation status
     */
    public function getDepositCreationInfo(): array
    {
        $deposits = $this->deposits()
            ->where('source_type', 'booking')
            ->get();
        
        return [
            'total_deposits' => $deposits->count(),
            'negative_count' => $deposits->where('amount', '<', 0)->count(),
            'positive_count' => $deposits->where('amount', '>', 0)->count(),
            'deposits' => $deposits,
        ];
    }

    public function getDisplayTotalAmount(): float
    {
        if ($this->type !== 'play_together' || !$this->playTogether) {
            return (float) $this->amount;
        }
    
        if ($this->pay_by === 'host') {
            return (float) $this->amount + ($this->playTogether->price_per_person ?? 0);
        }
    
        $lapangPerOrang = $this->amount / $this->playTogether->max_participants;
        $joinFee = $this->playTogether->price_per_person ?? 0;
    
        return round($lapangPerOrang + $joinFee, 2);
    }    

    public function getMidtransChargeAmount(): float
    {
        // REGULAR
        if ($this->type === 'regular') {
            return (float) $this->amount;
        }

        // PLAY TOGETHER / SPARRING
        if ($this->type === 'play_together' && $this->playTogether) {

            // HOST BAYAR
            if ($this->pay_by === 'host') {
                return $this->getDisplayTotalAmount();
            }

            // PARTICIPANT BAYAR → TAGIH PER USER
            $userId = auth()->id();

            $payment = $this->participantPayments()
                ->where('user_id', $userId)
                ->whereNull('paid_at')
                ->first();        

            return (float) ($payment?->amount ?? 0);
        }

        return (float) $this->amount;
    }



/**
 * Mengecek apakah user masih punya sisa pembayaran
 */
public function hasRemainingPaymentForUser(int $userId): bool
{
    if ($this->type !== 'play_together') {
        return false; // Hanya berlaku untuk play_together
    }

    // Kalau host bayar full, participant tidak perlu bayar
    if ($this->pay_by === 'host') return false;

    // Jumlah deposit user yang sudah completed
    $paid = $this->participantPayments()
        ->where('user_id', $userId)
        ->whereNotNull('paid_at') // hanya yang sudah dibayar
        ->sum('amount');

    // Hitung jumlah seharusnya dibayar user
    $totalOwed = $this->getParticipantTotalCost();

    return $paid < $totalOwed;
}



public function getLapangPerPerson(): float
{
    if ($this->pay_by !== 'participant') return 0;

    if (!$this->playTogether || !$this->playTogether->max_participants) {
        return 0;
    }

    return (float) ceil(
        $this->amount / max(1, $this->playTogether->max_participants)
    );
}

public function getJoinFee(): float
{
    if (!$this->playTogether) return 0;

    // Cek berbagai field name untuk join fee
    return (float) ($this->playTogether->join_price 
        ?? $this->playTogether->price_per_person 
        ?? 0);
}

public function getParticipantTotalCost(): float
{
    if (!$this->playTogether) return 0;

    // REVERT: Kembali ke logika penjumlahan (Bagi Lapang + Input User)
    // Sesuai permintaan user agar biaya per person tidak 'dihapus/dikurangi' oleh bagi hasil lapang.
    
    $total = 0;

    // 1. Tambah bagian lapangan jika mode split bill
    if ($this->pay_by === 'participant') {
        $total += $this->getLapangPerPerson();
    }

    // 2. Tambah input price jika mode berbayar
    if ($this->playTogether->type === 'paid') {
        $total += (float) ($this->playTogether->price_per_person ?? 0);
    }

    return (float) $total;
}

/**
 * Get participant payment amount dengan fallback ke remaining_to_pay
 * Digunakan untuk menampilkan jumlah yang harus dibayar participant
 */
public function getParticipantPaymentAmount(int $userId): float
{
    if ($this->type !== 'play_together' || !$this->playTogether) {
        return 0;
    }

    // FIX: Jika host yang bayar lapangan (pay_by == host), 
    // Participant HANYA bayar jika tipe aktivitas-nya 'paid' (Join Fee).
    // Jika 'free' maka participant 0.
    if ($this->pay_by === 'host' && $this->playTogether->type !== 'paid') {
        return 0;
    }

    // Ambil data participant
    $participant = $this->playTogether->participants()
        ->where('user_id', $userId)
        ->first();

    if (!$participant) {
        return 0;
    }

    // Gunakan calculated value (amount - total_paid)
    $remaining = (float)($participant->amount ?? 0) - (float)($participant->total_paid ?? 0);
    return max(0, $remaining);

    // Calculate dari biaya lapang + join fee
    return $this->getParticipantTotalCost();
}

/**
 * Cek apakah harus menampilkan tombol bayar untuk participant
 */
public function shouldShowPayButtonFor(int $userId): bool
{
    if ($this->type !== 'play_together' || !$this->playTogether) {
        return false;
    }

    // Ambil data participant
    $participant = $this->playTogether->participants()
        ->where('user_id', $userId)
        ->where('approval_status', 'approved')
        ->first();

    if (!$participant) {
        return false;
    }

    // Jangan tampilkan kalau sudah paid
    if ($participant->payment_status === 'paid') {
        return false;
    }

    // Cek apakah ada biaya yang harus dibayar
    // Jika biaya total 0, maka tidak butuh bayar
    return $this->getParticipantTotalCost() > 0;
}

}