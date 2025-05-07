<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'evaluator_id',
        'evaluatee_id',
        'rating',
        'comment',
    ];

    // 取引とのリレーション（N:1）
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // 評価をしたユーザー
    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    // 評価されたユーザー
    public function evaluatee()
    {
        return $this->belongsTo(User::class, 'evaluatee_id');
    }
}
