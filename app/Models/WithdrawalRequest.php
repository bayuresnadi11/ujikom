<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WithdrawalRequest extends Model
{
    use HasFactory;

    protected $table = 'withdrawal_requests';

    /**
     * HANYA FIELD INI YANG BOLEH DIISI (mass assignment)
     * Field lain akan diisi oleh sistem atau admin
     */
    protected $fillable = [
        'user_id',              // Diisi otomatis dari Auth::id()
        'amount',               // Diisi user
        'bank_name',            // Diisi user
        'account_number',       // Diisi user
        'account_holder_name',  // Diisi user
        'photo',
        'status',               // Default 'pending', diubah oleh admin
        // 'rejection_reason',  // TIDAK fillable - hanya admin yang isi
        // 'processed_at',      // TIDAK fillable - hanya admin yang isi
        // 'processed_by',      // TIDAK fillable - hanya admin yang isi
    ];

    /**
     * Field yang TIDAK BOLEH diisi via mass assignment
     * Hanya bisa diisi via update manual atau admin
     */
    protected $guarded = [
        'rejection_reason',
        'processed_at',
        'processed_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    /* =====================
     | RELATIONSHIPS
     ===================== */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Relationship ke deposit terkait
     */
    public function deposit()
    {
        return $this->hasOne(Deposit::class, 'source_id')
            ->where('source_type', 'withdraw');
    }

    /* =====================
     | SCOPES
     ===================== */

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeProcessed($query)
    {
        return $query->where('status', 'processed');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /* =====================
     | HELPERS
     ===================== */

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Proses',
            'approved' => 'Disetujui',
            'processed' => 'Berhasil Ditransfer',
            'rejected' => 'Ditolak',
            default => 'Tidak Diketahui',
        };
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'pending' => 'badge-warning',
            'approved' => 'badge-info',
            'processed' => 'badge-success',
            'rejected' => 'badge-danger',
            default => 'badge-light',
        };
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isProcessed(): bool
    {
        return $this->status === 'processed';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if withdrawal can be cancelled by user
     */
    public function canBeCancelled(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Format amount for display
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Get days since request
     */
    public function getDaysSinceRequestAttribute(): int
    {
        return $this->created_at->diffInDays(now());
    }
}