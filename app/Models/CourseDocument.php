<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDocument extends Model
{
    protected $table = 'course_documents';

    protected $fillable = [
        'course_id',
        'teacher_id',
        'name',
        'description',
        'path'
    ];
}
