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

class Test extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tests';

    protected $fillable = [
        'limit_time',
        'test_name',
        'blind_support',
        'created_by',
        'course_id',
        'class_id'
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];

    public function questions() {
        return $this->hasMany('App\Models\Question', 'test_id', 'id');
    }
}
