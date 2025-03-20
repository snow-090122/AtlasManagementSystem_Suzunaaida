<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Posts\Like;
use App\Models\Categories\SubCategory;
use App\Models\Users\User;
use App\Models\Posts\PostComment;
class Post extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_title',
        'post',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function postComments(): HasMany
    {
        return $this->hasMany('App\Models\Posts\PostComment');
    }

    // サブカテゴリーのリレーション（多対多）
    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'post_sub_categories', 'post_id', 'sub_category_id');
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
