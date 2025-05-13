<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

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

    protected $appends = ['profile_image_url'];


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


    /**
     * プロフィール画像のURLを取得するアクセサ
     *
     * @return string
     */
    public function getProfileImageUrlAttribute()
    {
        return $this->profile_image
            ? Storage::url($this->profile_image)
            : asset('images/default_profile.png');
    }


        /**
     * 評価された取引のリレーション
     */
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'evaluatee_id');
    }

    /**
     * 平均評価を計算するアクセサ
     */
    public function getAverageRatingAttribute()
    {
        if ($this->evaluations()->count() === 0) {
            return null; // 評価がない場合は null を返す
        }

        // 評価の平均を計算し、四捨五入して返す
        return round($this->evaluations()->avg('rating'), 1);
    }

}
