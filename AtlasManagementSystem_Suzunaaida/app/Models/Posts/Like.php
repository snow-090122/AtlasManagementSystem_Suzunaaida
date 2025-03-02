<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'like_user_id',
        'like_post_id',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'like_post_id');
    }
}
