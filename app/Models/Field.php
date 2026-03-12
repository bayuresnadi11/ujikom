<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $table = 'fields';

    protected $fillable = [
        'created_by',
        'category_id',
        'name_field',
        'description',
        'location',
        'foto',
        'rating',
    ];

    // 🔗 Relasi ke kategori (dibuat admin)
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // 🔗 Pemilik lapangan
    public function landowner()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sections()
    {
        return $this->hasMany(SectionField::class, 'field_id');
    }
}
