<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    /**
     * リーレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * アクセサ - url
     * @return string
     */
    public function getUrlAttribute()
    {
        return Storage::cloud()->url($this->file_name);
    }
}
