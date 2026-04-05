<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'date',
        'start_time',
        'end_time',
        'available',
        'rental_price',
        'rental_duration',
    ];

    protected $casts = [
        'date' => 'date',
        // ✅ HAPUS casting start_time & end_time sebagai datetime
        // Biarkan sebagai string karena tipe TIME di database
        'available' => 'boolean',
    ];

    // Relationships
    public function venueSection()
    {
        return $this->belongsTo(VenueSection::class, 'section_id');
    }
    
    public function section()
    {
        return $this->belongsTo(VenueSection::class, 'section_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'schedule_id');
    }
}