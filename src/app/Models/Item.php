<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'category',
        'image_path',
        'is_sold',
    ];

    /**
     * アイテムを所有するユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * アイテムに関連する購入
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * アイテムに投稿されたコメント
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * アイテムに対する「いいね」
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * 商品の状態を人間が読める形式で取得
     *
     * @return string
     */
    public function getConditionTextAttribute()
    {
        $conditions = [
            1 => '良好',
            2 => '目立った傷や汚れなし',
            3 => 'やや傷や汚れあり',
            4 => '状態が悪い',
        ];

        return $conditions[$this->condition];
    }

    /**
     * 商品カテゴリーの選択肢
     *
     * @return array
     */
    public static function categories()
    {
        return [
            'ファッション',
            '家電',
            'インテリア',
            'レディース',
            'メンズ',
            'コスメ',
            '本',
            'ゲーム',
            'スポーツ',
            'キッチン',
            'ハンドメイド',
            'アクセサリー',
            'おもちゃ',
            'ベビー・キッズ',
        ];
    }

    /**
     * 商品画像のURLを取得
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return $this->image_path
            ? asset('storage/item_images/' . $this->image_path)
            : asset('images/default_item.png');
    }
}
