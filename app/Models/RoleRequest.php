<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'requested_role',
        'reason',
        'status',
        'admin_notes',
        'reviewed_by',
        'approved_at',
        'reviewed_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ========== SCOPE METHODS ==========
    
    // Cek apakah user sudah pernah diapprove jadi landowner
    public function scopeHasApprovedLandowner($query, $userId)
    {
        return $query->where('user_id', $userId)
                    ->where('requested_role', 'landowner')
                    ->where('status', 'approved');
    }

    // Cek apakah user ada request pending untuk jadi landowner
    public function scopeHasPendingLandownerRequest($query, $userId)
    {
        return $query->where('user_id', $userId)
                    ->where('requested_role', 'landowner')
                    ->where('status', 'pending');
    }

    // Ambil request approved terakhir
    public function scopeLastApprovedLandowner($query, $userId)
    {
        return $query->where('user_id', $userId)
                    ->where('requested_role', 'landowner')
                    ->where('status', 'approved')
                    ->latest()
                    ->first();
    }

    // ========== HELPER METHODS ==========
    
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}