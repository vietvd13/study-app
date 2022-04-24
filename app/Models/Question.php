<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'test_question';

    protected $fillable = [
        'test_id',
        'question',
        'question_audio'
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];

    public function answers() {
        return $this->hasMany('App\Models\Answer', 'question_id', 'id');
    }
}
