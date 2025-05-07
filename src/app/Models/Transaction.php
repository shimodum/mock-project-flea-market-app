<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'buyer_id',
        'status',
    ];

    // 商品とのリレーション（1:1）
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // 購入者（ユーザー）とのリレーション（1:1）
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // チャットメッセージとのリレーション（1:N）
    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    // 評価とのリレーション（1:N）
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}
