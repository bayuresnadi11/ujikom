<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\VenueSchedule;

class VenueSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'section_name',
        'description',
        'price_per_hour',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    // 🔥 INI YANG DIPAKAI
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'section_id');
    }

    public function venueSchedules()
    {
        return $this->hasMany(
            VenueSchedule::class,
            'section_id', // FK di venue_schedules
            'id'          // PK di venue_sections
        );
    }
}
