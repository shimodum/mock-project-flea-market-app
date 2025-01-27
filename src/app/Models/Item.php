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
        'image_path',
        'is_sold',
    ];

    // 商品を出品するユーザー
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 商品に関連する購入
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // 商品に投稿されたコメント
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // 商品に対する「いいね」
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // 多：多（中間テーブル：item_category を使用）
    // ItemとCategoryは多対多の関係にある
    public function categories()
    {
        // 第2引数: 中間テーブル名
        // 第3引数: Item側の外部キー (item_id)
        // 第4引数: Category側の外部キー (category_id)
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
