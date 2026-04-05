<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Deposit extends Model
{
    use HasFactory;

    protected $table = 'deposits';

    protected $fillable = [
        'user_id',
        'booking_id',
        'amount',
        'source_type',
        'source_id',
        'participant_id',
        'status',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /* =====================
     | RELATIONSHIPS
     ===================== */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function playTogether()
    {
        if ($this->source_type === 'play_together') {
            return $this->belongsTo(PlayTogether::class, 'source_id');
        }
        return null;
    }

    public function sparring()
    {
        if ($this->source_type === 'sparring') {
            return $this->belongsTo(Sparring::class, 'source_id');
        }
        return null;
    }

    public function participant()
    {
        if ($this->participant_id) {
            return $this->belongsTo(User::class, 'participant_id');
        }
        return null;
    }

    public function withdrawal()
    {
        if ($this->source_type === 'withdraw') {
            return $this->belongsTo(WithdrawalRequest::class, 'source_id');
        }
        return null;
    }

    /* =====================
     | SCOPES
     ===================== */

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeFromBooking($query)
    {
        return $query->where('source_type', 'booking');
    }

    public function scopeFromPlayTogether($query)
    {
        return $query->where('source_type', 'play_together');
    }

    public function scopeBookingNegative($query)
    {
        return $query->where('source_type', 'booking')
                    ->where('amount', '<', 0);
    }

    public function scopeBookingPositive($query)
    {
        return $query->where('source_type', 'booking')
                    ->where('amount', '>', 0);
    }

    public function scopeForBooking($query, $bookingId)
    {
        return $query->where('booking_id', $bookingId)
                    ->where('source_type', 'booking');
    }

    public function scopeFromVenues($query, $venueIds)
    {
        return $query->whereHas('booking', function ($query) use ($venueIds) {
            $query->whereIn('venue_id', $venueIds);
        });
    }

    public function scopeBookingCompleted($query)
    {
        return $query->where('source_type', 'booking')
                    ->where('status', 'completed');
    }

    /* =====================
     | SCOPES BARU UNTUK LANDOWNER
     ===================== */

    public function scopeForLandowner($query, $userId)
    {
        return $query->where('source_type', 'booking')
            ->where('amount', '>', 0)
            ->where('status', 'completed')
            ->whereHas('booking.venue', function ($q) use ($userId) {
                $q->where('created_by', $userId);
            });
    }

    public function scopeAllBookingDepositsForLandowner($query, $userId)
    {
        return $query->where('source_type', 'booking')
            ->whereHas('booking.venue', function ($q) use ($userId) {
                $q->where('created_by', $userId);
            });
    }

    public function scopeWithdrawableForLandowner($query, $userId)
    {
        return $query->where('source_type', 'booking')
            ->where('amount', '>', 0)
            ->where('status', 'completed')
            ->whereHas('booking.venue', function ($q) use ($userId) {
                $q->where('created_by', $userId);
            });
    }

    /* =====================
     | HELPERS
     ===================== */

    public function getSourceName(): string
    {
        return match($this->source_type) {
            'booking' => 'Booking Lapangan',
            'play_together' => 'Main Bareng',
            'sparring' => 'Sparring',
            'manual_deposit' => 'Top Up Manual',
            'withdraw' => 'Penarikan Saldo',
            'refund' => 'Pengembalian Dana',
            default => 'Transaksi Lain',
        };
    }

    public function getSourceIcon(): string
    {
        return match($this->source_type) {
            'booking' => 'fas fa-calendar-alt',
            'play_together' => 'fas fa-users',
            'sparring' => 'fas fa-fist-raised',
            'manual_deposit' => 'fas fa-money-bill-wave',
            'withdraw' => 'fas fa-hand-holding-usd',
            'refund' => 'fas fa-undo',
            default => 'fas fa-exchange-alt',
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'completed' => 'Selesai',
            'canceled' => 'Dibatalkan',
            default => 'Tidak Diketahui',
        };
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'pending' => 'badge-warning',
            'completed' => 'badge-success',
            'canceled' => 'badge-danger',
            default => 'badge-light',
        };
    }

    public function isPositive(): bool
    {
        return $this->amount > 0;
    }

    public function isNegative(): bool
    {
        return $this->amount < 0;
    }

    public function isFromBooking(): bool
    {
        return $this->source_type === 'booking';
    }

    public function getBookingDepositAmount(): float
    {
        if ($this->source_type !== 'booking') {
            return 0;
        }
        return (float) $this->amount;
    }

    public function isBookingNegativeDeposit(): bool
    {
        return $this->isFromBooking() && $this->isNegative();
    }

    public function isBookingPositiveDeposit(): bool
    {
        return $this->isFromBooking() && $this->isPositive();
    }

    public function getBookingPaymentStatus(): ?string
    {
        if (!$this->booking_id) {
            return null;
        }
        
        return $this->booking?->booking_payment;
    }

    public static function createNegativeDepositForBooking(Booking $booking): Deposit
    {
        return self::create([
            'user_id' => $booking->user_id,
            'booking_id' => $booking->id,
            'amount' => -$booking->amount,
            'source_type' => 'booking',
            'source_id' => $booking->id,
            'status' => 'pending',
            'description' => 'Booking dibuat - status: ' . strtoupper($booking->booking_payment),
        ]);
    }

    public static function createPositiveDepositForBooking(Booking $booking): Deposit
    {
        return self::create([
            'user_id' => $booking->user_id,
            'booking_id' => $booking->id,
            'amount' => $booking->amount,
            'source_type' => 'booking',
            'source_id' => $booking->id,
            'status' => 'completed',
            'description' => 'Booking dibayar - status: ' . strtoupper($booking->booking_payment),
        ]);
    }

    public static function updateForBookingPayment($bookingId, $paymentStatus, $amount): void
    {
        $booking = Booking::find($bookingId);
        if (!$booking) {
            return;
        }

        $negativeDeposit = self::where('booking_id', $bookingId)
            ->where('source_type', 'booking')
            ->where('amount', '<', 0)
            ->where('status', 'pending')
            ->first();

        if ($paymentStatus === 'full' || $paymentStatus === 'partial') {
            if ($negativeDeposit) {
                $negativeDeposit->update([
                    'status' => 'completed',
                    'description' => 'Booking ' . $paymentStatus . ' paid - negative deposit completed',
                ]);
            }

            self::createPositiveDepositForBooking($booking);
        } elseif ($paymentStatus === 'pending') {
            if (!$negativeDeposit) {
                self::createNegativeDepositForBooking($booking);
            }
        }
    }

    public static function getWithdrawableAmount($userId): float
    {
        $completedPositiveDeposits = self::where('user_id', $userId)
            ->where('status', 'completed')
            ->where('amount', '>', 0)
            ->whereIn('source_type', ['play_together', 'sparring'])
            ->sum('amount');

        $withdrawnAmount = self::where('user_id', $userId)
            ->where('source_type', 'withdraw')
            ->where('status', 'completed')
            ->sum(DB::raw('ABS(amount)'));

        return max(0, (float)$completedPositiveDeposits - (float)$withdrawnAmount);
    }

    public static function getBookingDepositSummary($userId): array
    {
        $deposits = self::where('user_id', $userId)
            ->where('source_type', 'booking')
            ->with('booking')
            ->orderBy('created_at', 'desc')
            ->get();

        $negativeDeposits = $deposits->where('amount', '<', 0);
        $positiveDeposits = $deposits->where('amount', '>', 0);
        $pendingDeposits = $deposits->where('status', 'pending');
        $completedDeposits = $deposits->where('status', 'completed');

        return [
            'total_negative' => $negativeDeposits->sum('amount'),
            'total_positive' => $positiveDeposits->sum('amount'),
            'total_pending' => $pendingDeposits->count(),
            'total_completed' => $completedDeposits->count(),
            'net_balance' => $deposits->sum('amount'),
            'deposits' => $deposits,
        ];
    }

    public static function calculateCurrentBalance($userId): float
    {
        $deposits = self::where('user_id', $userId)
            ->get();

        $balance = 0;
        
        foreach ($deposits as $deposit) {
            if ($deposit->source_type === 'booking') {
                if ($deposit->status === 'pending' || $deposit->status === 'completed') {
                    $balance += $deposit->amount;
                }
            } else {
                if ($deposit->status === 'completed') {
                    $balance += $deposit->amount;
                }
            }
        }
        
        return $balance;
    }

    /* =====================
     | METHOD BARU UNTUK LANDOWNER DENGAN WITHDRAWAL
     ===================== */

    public static function getTotalProcessedWithdrawalAmount($userId): float
    {
        return WithdrawalRequest::where('user_id', $userId)
            ->where('status', 'processed')
            ->sum('amount');
    }

    public static function getTotalBalanceForLandowner($userId): float
    {
        $totalDeposit = self::where('source_type', 'booking')
            ->where('amount', '>', 0)
            ->where('status', 'completed')
            ->whereHas('booking.venue', function ($query) use ($userId) {
                $query->where('created_by', $userId);
            })
            ->sum('amount');

        $totalWithdrawal = self::getTotalProcessedWithdrawalAmount($userId);

        return max(0, (float)$totalDeposit - (float)$totalWithdrawal);
    }

    public static function getWithdrawableAmountForLandowner($userId): float
    {
        $totalDeposit = self::where('source_type', 'booking')
            ->where('amount', '>', 0)
            ->where('status', 'completed')
            ->whereHas('booking.venue', function ($query) use ($userId) {
                $query->where('created_by', $userId);
            })
            ->sum('amount');

        $totalWithdrawal = self::getTotalProcessedWithdrawalAmount($userId);

        $availableBalance = max(0, (float)$totalDeposit - (float)$totalWithdrawal);

        $pendingWithdrawal = WithdrawalRequest::where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');

        return max(0, $availableBalance - $pendingWithdrawal);
    }

    public static function getDepositHistoryForLandowner($userId, $limit = 50)
    {
        return self::where('source_type', 'booking')
            ->where('amount', '>', 0)
            ->whereHas('booking.venue', function ($query) use ($userId) {
                $query->where('created_by', $userId);
            })
            ->with(['booking' => function($query) {
                $query->with(['venue', 'user']);
            }])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getWithdrawalHistoryForLandowner($userId)
    {
        return WithdrawalRequest::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($withdrawal) {
                $withdrawal->status_label = match($withdrawal->status) {
                    'pending' => 'Menunggu',
                    'approved' => 'Disetujui',
                    'processed' => 'Diproses',
                    'rejected' => 'Ditolak',
                    default => 'Tidak Diketahui'
                };
                
                $withdrawal->status_class = match($withdrawal->status) {
                    'pending' => 'status-pending',
                    'approved' => 'status-approved',
                    'processed' => 'status-processed',
                    'rejected' => 'status-rejected',
                    default => ''
                };
                
                return $withdrawal;
            });
    }

    
    public static function getLandownerBalanceDetails($userId): array
    {
        $totalDeposit = self::where('source_type', 'booking')
            ->where('amount', '>', 0)
            ->where('status', 'completed')
            ->whereHas('booking.venue', function ($query) use ($userId) {
                $query->where('created_by', $userId);
            })
            ->sum('amount');

        $totalWithdrawalProcessed = self::getTotalProcessedWithdrawalAmount($userId);
        
        $totalWithdrawalPending = WithdrawalRequest::where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');

        $availableBalance = max(0, (float)$totalDeposit - (float)$totalWithdrawalProcessed);
        
        $withdrawableAmount = max(0, $availableBalance - $totalWithdrawalPending);

        return [
            'total_deposit' => (float)$totalDeposit,
            'total_withdrawal_processed' => (float)$totalWithdrawalProcessed,
            'total_withdrawal_pending' => (float)$totalWithdrawalPending,
            'available_balance' => (float)$availableBalance,
            'withdrawable_amount' => (float)$withdrawableAmount,
            'has_pending_withdrawal' => $totalWithdrawalPending > 0,
        ];
    }
}