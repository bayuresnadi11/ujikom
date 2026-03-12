<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'method',
        'amount',
        'status',
        'order_id',
        'snap_token',
        'payment_url',
        'payment_date',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}