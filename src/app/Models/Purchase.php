<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
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
        'payment_method',
        'shipping_address',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payment_method' => 'string', // payment_method を string 型として扱う
    ];

    // 購入を行ったユーザー
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 購入された商品
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * 送付先住所を適切に分割して表示
     *
     * @return string
     */
    public function getFormattedShippingAddressAttribute()
    {
        $address = $this->shipping_address;
        // 住所が改行を含んでいる場合（例: 郵便番号、住所、建物名）
        $addressLines = explode("\n", $address);
        
        // 住所が複数行に分かれている場合、配列として返す
        return [
            'postal_code' => $addressLines[0] ?? '',
            'address' => $addressLines[1] ?? '',
            'building' => $addressLines[2] ?? '',
        ];
    }
}
