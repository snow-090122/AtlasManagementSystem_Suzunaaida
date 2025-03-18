<?php

namespace App\Models\Users;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use App\Models\Posts\Like;
use App\Models\Subjects\Subject;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'over_name',
        'under_name',
        'over_name_kana',
        'under_name_kana',
        'mail_address',
        'sex',
        'birth_day',
        'role',
        'password'
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany('App\Models\Posts\Post');
    }

    public function calendars()
    {
        return $this->belongsToMany('App\Models\Calendars\Calendar', 'calendar_users', 'user_id', 'calendar_id')->withPivot('user_id', 'id');
    }

    public function reserveSettings()
    {
        return $this->belongsToMany('App\Models\Calendars\ReserveSettings', 'reserve_setting_users', 'user_id', 'reserve_setting_id')->withPivot('id');
    }

    // subjects とのリレーションを定義
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'user_id');
    }

    // ✅ いいねリレーションを定義
    public function likes()
    {
        return $this->hasMany(Like::class, 'like_user_id');
    }

    // ✅ いいねしているかどうかを判定
    public function is_Like($post_id)
    {
        return $this->likes()->where('like_post_id', $post_id)->exists(); // ✅ `exists()` を使って boolean を返す
    }

    // ✅ いいねした投稿の ID リストを取得
    public function likePostId()
    {
        return $this->likes()->pluck('like_post_id')->toArray(); // ✅ 配列で返す
    }

    public function getAuthIdentifierName()
    {
        return 'mail_address';
    }
}
