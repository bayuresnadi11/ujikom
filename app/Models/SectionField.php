<?php

namespace App\Models;
use App\Models\NamaModelLain;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Field;

class SectionField extends Model
{
    use HasFactory;

    protected $table = 'section_fields';
    protected $fillable = [
        'field_id', 'name_section', 'description', 
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
}