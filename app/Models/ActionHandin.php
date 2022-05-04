<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionHandin extends Model
{
    use HasFactory;
    const STUDENT_ID = 'student_id';
    const ACTION_ID = 'action_id';
    const DESCRIPTION = 'description';
    const FILE_PATH = 'file_path';
    const PIVOT = 'pivot';
    protected $table = 'student_handin';

    protected $hidden = [
        ClassAction::PIVOT,
    ];

    protected $fillable = [
        ActionHandin::STUDENT_ID,
        ActionHandin::ACTION_ID,
        ActionHandin::DESCRIPTION,
        ActionHandin::FILE_PATH
    ];

    public function grading() {
        return $this->hasMany('App\Models\ClassAction', 'class_id', 'id');
    }

    public function student() {
        return $this->hasOne('App\Models\User', 'id', 'student_id');
    }
}
