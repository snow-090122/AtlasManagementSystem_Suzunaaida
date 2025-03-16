<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Categories\MainCategory;
use App\Models\Posts\Post;

class SubCategory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'main_category_id',
        'sub_category',
    ];

    public function mainCategory(): BelongsTo
    {
        return $this->belongsTo(MainCategory::class, 'main_category_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'sub_category_id'); // 修正
    }
}
