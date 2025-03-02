<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;
use App\Models\Posts\Like;

class Post extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_title',
        'post',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Users\User');
    }

    public function postComments()
    {
        return $this->hasMany('App\Models\Posts\PostComment');
    }

    public function subCategories()
    {
        // リレーションの定義
    }

    // いいねのリレーションを定義
    public function likes()
    {
        return $this->hasMany(Like::class, 'like_post_id');
    }

    // いいね数を取得するメソッド
    public function likeCounts()
    {
        return $this->likes()->count();
    }
}
