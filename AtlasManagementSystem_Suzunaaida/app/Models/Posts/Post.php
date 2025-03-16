<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Posts\Like;
use App\Models\Categories\SubCategory;

class Post extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_title',
        'post',
        'sub_category_id', // 追加
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\Users\User');
    }

    public function postComments(): HasMany
    {
        return $this->hasMany('App\Models\Posts\PostComment');
    }

    //サブカテゴリーのリレーション
    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    // いいねのリレーションを定義
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class, 'like_post_id');
    }

    // いいね数を取得するメソッド
    public function likeCounts()
    {
        return $this->likes()->count();
    }
}
