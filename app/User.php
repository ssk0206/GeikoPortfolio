<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * リレーションシップ filesテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany('App\File');
    }

    /**
     * リレーションシップ usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function follows()
    {
        return $this->belongsToMany('App\User', 'relationships', 'follower_id', 'follow_id');
    }

    /**
     * リレーションシップ usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany('App\User', 'relationships', 'follow_id', 'follower_id');
    }

    /**
     * アクセサ - follows_count
     * @return int
     */
    public function getFollowsCountAttribute()
    {
        return $this->follows->count();
    }

    /**
     * アクセサ - followers_count
     * @return int
     */
    public function getFollowersCountAttribute()
    {
        return $this->followers->count();
    }

    /**
     * アクセサ - followed_by_user
     * @return boolean
     */
    public function getFollowedByUserAttribute()
    {
        if (Auth::guest()) {
            return false;
        }

        return $this->followers->contains(function ($user) {
            return $user->id === Auth::user()->id;
        });
    }
}
