<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparringRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'sparring_id',
        'requester_id',
        'target_id',
        'status',
        'requested_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
    ];

    // Relationships
    public function sparring()
    {
        return $this->belongsTo(Sparring::class);
    }
}