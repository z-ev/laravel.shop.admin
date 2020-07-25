<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;


class User extends Model
{
    use SoftDeletes;
    use Notifiable;

    protected $fillable = [
        'name',
        'login',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_st'=>'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'user_roles');
    }

}
