<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'quiz_id',
        'question_number',
        'question_text',
        'sub_question',
        'question_table',
        'question_image',
        'question_sound',
    ];
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
