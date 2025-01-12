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
     * 購入を行ったユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 購入されたアイテム
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * 支払い方法を人間が読める形式で取得
     *
     * @return string
     */
    public function getPaymentMethodTextAttribute()
    {
        $paymentMethods = [
            1 => 'コンビニ払い',
            2 => 'カード支払い',
        ];

        return $paymentMethods[$this->payment_method];
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
