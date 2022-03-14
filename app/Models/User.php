<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //@@46_03:40 - mutator
        // Upon registration, bycrypt the password
        // "setPasswordAttribute" need to follow the naming convention "set", "Password", "Attribute".
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function posts() // This name convention matters as Laravel looks for matching id (post_id)
    // Notice it's posts(plural), but singular on the other side (author)
    {
        return $this->hasMany(Post::class);
    }
}
