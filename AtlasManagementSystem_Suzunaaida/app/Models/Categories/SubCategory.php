<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Categories\MainCategory;
use App\Models\Posts\Post;

class SubCategory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'main_category_id',
        'sub_category_name',
    ];

    public function mainCategory(): BelongsTo
    {
        return $this->belongsTo(MainCategory::class, 'main_category_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'post_category_id');
    }
}
