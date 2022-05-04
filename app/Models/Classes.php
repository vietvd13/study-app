<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;
    const NAME = 'name';
    const LEVEL = 'level';
    const START_DATE = 'start_date';
    const END_DATE = 'end_date';
    const PIVOT = 'pivot';
    protected $hidden = [
        Classes::PIVOT,
    ];
    protected $fillable = [
        Classes::NAME,
        Classes::LEVEL,
        Classes::START_DATE,
        Classes::END_DATE
    ];

    public function students() {
        return $this->belongsToMany('App\Models\User', 'class_student', 'class_id', 'student_id')->withTimestamps();
    }

    public function courses() {
        return $this->belongsToMany('App\Models\Course', 'class_course', 'class_id', 'course_id')->withTimestamps();
    }

    public function class_action() {
        return $this->hasMany('App\Models\ClassAction', 'class_id', 'id');
    }
}
