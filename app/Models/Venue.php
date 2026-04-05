<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'category_id',
        'venue_name',
        'description',
        'location',
        'rating',
        'rating',
        'photo', // Deprecated? Keep for backward compat for now until migrated
    ];

    // Relationships
    public function photos()
    {
        return $this->hasMany(VenuePhoto::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function venueSections()
    {
        return $this->hasMany(VenueSection::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function playTogetherEvents()
    {
        return $this->hasMany(PlayTogether::class);
    }

    public function sparringEvents()
    {
        return $this->hasMany(Sparring::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}