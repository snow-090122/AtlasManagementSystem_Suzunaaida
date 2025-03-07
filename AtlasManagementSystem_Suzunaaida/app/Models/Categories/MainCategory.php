<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Categories\SubCategory;

class MainCategory extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = ['main_category'];

    public function subCategories(): HasMany
    {
        return $this->hasMany(SubCategory::class, 'main_category_id');
    }
}
