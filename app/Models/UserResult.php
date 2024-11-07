<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'exam_id',
        'exam_title',
        'total_questions',
        'total_attempts',
        'total_correct',
        'percentage',
        'correct_count',
        'incorrect_count',
        'unsolved_count',
    ];
}

