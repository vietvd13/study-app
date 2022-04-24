<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $table = 'question_anwser';

    protected $fillable = [
        'question_id',
        'answer',
        'is_correct'
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];
}
