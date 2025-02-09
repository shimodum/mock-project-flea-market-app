<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'postal_code',
        'address',
        'address_building',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    
    // ユーザーが出品する商品
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    
    // ユーザーが行った購入
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    
    // ユーザーが投稿したコメント
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    
    // ユーザーが「いいね」した商品
    public function likes()
    {
        return $this->hasMany(Like::class);
    }


    protected $appends = ['profile_image_url']; 

    /**
     * プロフィール画像のURLを取得
     *
     * @return string
     */
    public function getProfileImageUrlAttribute()
    {
        if (!empty($this->attributes['profile_image'])) {
            return asset('storage/' . $this->attributes['profile_image']);
        }

        return asset('images/default_profile.png');
    }

}
