<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;

class PostComment extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'post_id',
        'user_id',
        'comment',
    ];

    // 投稿へのリレーション
    public function post()
    {
        return $this->belongsTo('App\Models\Posts\Post');
    }

    // ユーザーへのリレーション
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
