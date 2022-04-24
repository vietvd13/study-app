<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;

    protected $table = 'student_answer';

    protected $fillable = [
        'student_id',
        'question_id',
        'answer_id'
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];
}
