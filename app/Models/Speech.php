<?php
/**
 * Created by TienNamNguyen.
 * User: namnt
 * Year: 2022-03-29
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Speech extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'speechs';

    protected $fillable = [];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];

}
