<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Venue;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'total_revenue',
        'total_transactions',
        'total_venue',
        'venue_id',
        'venue_name',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }
}