<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'description',
        'logo',
    ];

    // Relationships
    public function venues()
    {
        return $this->hasMany(Venue::class);
    }

    public function playTogetherEvents()
    {
        return $this->hasMany(PlayTogether::class);
    }

    public function sparringEvents()
    {
        return $this->hasMany(Sparring::class);
    }
}