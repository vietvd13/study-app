<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    const NAME = 'name';
    const CODE = 'user_code';
    const PHONE = 'phone';
    const EMAIL = 'email';
    const PASSWORD = 'password';
    const NEW_PASSWORD = 'new_password';
    const STATUS = 'status';
    const BLIND = 'isBlind';
    const ROLE = 'role';
    protected $hidden = [
        User::PASSWORD,
        'pivot'
    ];
    protected $fillable = [
        User::NAME,
        User::EMAIL,
        User::PASSWORD,
        User::PHONE,
        User::CODE,
        User::STATUS,
        User::BLIND
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */

    /**
     * The attributes that should be cast.
     *
     * @var array
     */

    protected $guard_name = 'api';

    public function classes() {
        return $this->belongsToMany('App\Models\Classes', 'class_student', 'student_id', 'class_id')->withTimestamps();
    }
}
