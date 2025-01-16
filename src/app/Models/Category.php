<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * 一括代入を許可するカラム
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];


    // 多対多（中間テーブル： item_category を使用）
    // CategoryとItemは多対多の関係にある
    public function items()
    {
        // 第2引数: 中間テーブル名
        // 第3引数: Category側の外部キー (category_id)
        // 第4引数: Item側の外部キー (item_id)
        return $this->belongsToMany(Item::class, 'item_category', 'category_id', 'item_id');
    }
}
