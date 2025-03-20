<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostSubCategory extends Model
{
    protected $table = 'post_sub_categories';

    protected $fillable = [
        'post_id',
        'sub_category_id'
    ];

    public $timestamps = true;
}
