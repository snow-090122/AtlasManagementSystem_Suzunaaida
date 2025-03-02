<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'like_user_id',
        'like_post_id'
    ];

    public static function likeCounts($post_id)
    {
        return self::where('like_post_id', $post_id)->count();
    }
}
