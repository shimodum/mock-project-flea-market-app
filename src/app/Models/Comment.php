<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'item_id',
        'content',
    ];

    // コメントを投稿したユーザー
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // コメントが付けられた商品
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
