<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassAction extends Model
{
    use HasFactory;
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const TEACHER_ID = 'teacher_id';
    const CLASS_ID = 'class_id';
    const PIVOT = 'pivot';
    protected $table = 'class_action';
    protected $hidden = [
        ClassAction::PIVOT,
    ];
    protected $fillable = [
        ClassAction::NAME,
        ClassAction::DESCRIPTION,
        ClassAction::TEACHER_ID,
        ClassAction::CLASS_ID
    ];

    public function action_handin() {
        return $this->hasMany('App\Models\ActionHandin', 'action_id', 'id');
    }
}
