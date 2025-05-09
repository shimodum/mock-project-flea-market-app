<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'user_id',
        'message',
        'is_read',
        'image_path',
    ];

    // 取引とのリレーション（N:1）
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // ユーザーとのリレーション（N:1）
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
