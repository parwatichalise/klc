<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    protected $fillable = [
        'heading',
        'sub_heading',
        'price',
        'photo',
        'time_duration',
        'active',
    ];
    public function tags() {
        return $this->belongsToMany(Tag::class); // Adjust the Tag class if needed
    }


}