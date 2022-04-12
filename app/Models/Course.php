<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-04-01
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'courses';
    const NAME = 'name';
    const PIVOT = 'pivot';
    protected $hidden = [
        Course::PIVOT,
    ];
    protected $fillable = [
        Course::NAME
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];

    public function teachers() {
        return $this->belongsToMany('App\Models\User', 'teacher_course', 'course_id', 'teacher_id')->withTimestamps();
    }

    public function files() {
        return $this->hasMany('App\Models\CourseDocument', 'course_id', 'id');
    }
}
