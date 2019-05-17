<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;

    protected $datas = ['deleted_at'];

    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * リレーションシップ - commentsテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        // return $this->hasMany('App\Comment')->orderBy('id', 'desc');
        return $this->hasMany('App\Comment');
    }

    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function likes()
    {
        // withTimestamps()
        // likes tableにデータを挿入したとき、created_at, updated_atを更新させるため
        return $this->belongsToMany('App\User', 'likes')->withTimestamps();
    }

    /**
     * アクセサ - image_url
     * @return string
     */
    public function getImageUrlAttribute()
    {
        // return Storage::cloud()->url($this->s3_name);
        $folder = substr($this->s3_name,0, 12);
        return Storage::cloud()->url($this->s3_name);
    }

    /**
     * アクセサ - movie_url
     * @return string
     */
    public function getMovieUrlAttribute()
    {
        // return Storage::cloud()->url($this->s3_name);
        $folder = substr($this->s3_name,0, 12);
        $movie = 'https://s3-ap-northeast-1.amazonaws.com/transcoder-data/'.$folder.'/'.$this->s3_name;
        return $movie;
    }

    /**
     * アクセサ - folder
     */
    public function getFolderAttribute()
    {
        $folder = mb_substr($this->s3_name,0, 12);
        \Log::info('folder');
        return $folder;
    }

    /**
     * アクセサ - thumb
     * @return string
     */
    public function getThumbAttribute()
    {
        $folder = mb_substr($this->s3_name,0, 12);
        $file = mb_substr($this->s3_name,0, 12) . '-00001.png';
        //\Log::info('https://s3-ap-northeast-1.amazonaws.com/transcoder-data/'.$folder.'/thumbnail-'.$file);
        $thumb = 'https://s3-ap-northeast-1.amazonaws.com/transcoder-data/'.$folder.'/thumbnail-'.$file;
        return $thumb;
    }

    /**
     * アクセサ - likes_count
     * @return int
     */
    public function getLikesCountAttribute()
    {
        return $this->likes->count();
    }

    /**
     * アクセサ - liked_by_user
     * @return boolean
     */
    public function getLikedByUserAttribute()
    {
        if (Auth::guest()) {
            return false;
        }

        return $this->likes->contains(function ($user) {
            return $user->id === Auth::user()->id;
        });
    }
}
