<?php

namespace App\Models\Subjects;

use Illuminate\Database\Eloquent\Model;

use App\Models\Users\User;

class Subject extends Model
{
    const UPDATED_AT = null;


    protected $fillable = [
        'subject',
        'user_id',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
