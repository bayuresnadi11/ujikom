<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'venue_schedules';

    protected $fillable = [
        'section_id',
        'date',
        'start_time',
        'end_time',
        'available',
        'price',
        'duration',
    ];

    public function section()
    {
        return $this->belongsTo(SectionField::class, 'section_id');
    }

    // 🔥 INI YANG KAMU BELUM PUNYA
    public function booking()
    {
        return $this->hasOne(Booking::class, 'schedule_id');
    }
}
