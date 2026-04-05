<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'community_id',
        'image_path',
    ];

    public function community()
    {
        return $this->belongsTo(Community::class);
    }
}
