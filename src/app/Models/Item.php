<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'condition',
        'brand',
        'image_path',
        'is_sold',
    ];

    /**
     * 商品を出品するユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 商品に関連する購入
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * 商品に投稿されたコメント
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * 商品に対する「いいね」
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * 商品が所属するカテゴリ（多対多）
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_category', 'item_id', 'category_id');
    }

    /**
     * 商品の状態を人間が読める形式で取得
     *
     * @return string
     */
    public function getConditionLabelAttribute()
    {
        $conditions = [
            1 => '良好',
            2 => '目立った傷や汚れなし',
            3 => 'やや傷や汚れあり',
            4 => '状態が悪い',
        ];

        return $conditions[$this->condition] ?? '不明';
    }

    /**
     * 商品画像のURLを取得するアクセサ
     *
     * @return string|null
     */
    public function getImageUrlAttribute()
    {
        // すでにURL形式の場合はそのまま返す
        if (preg_match('/^https?:\/\//', $this->image_path)) {
            return $this->image_path;
        }

        // ローカルのstorageパスを返す
        return $this->image_path
            ? Storage::url($this->image_path)
            : asset('images/default-item.png');
    }

    /**
     * 指定したユーザーがこの商品を「いいね」しているか判定
     *
     * @param \App\Models\User|null $user
     * @return bool
     */
    public function isLikedBy($user)
    {
        if (!$user) {
            return false;
        }
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
