<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Item;
use App\Models\User;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        // 既存のデータを削除
        Transaction::truncate();

        // User1 の出品商品 (CO01 ～ CO05) を User2 が購入する取引
        for ($i = 1; $i <= 5; $i++) {
            Transaction::create([
                'item_id' => $i,
                'buyer_id' => 2, // User2 が購入者
                'status' => 'negotiating', // 交渉中のステータス
            ]);
        }

        // User2 の出品商品 (CO06 ～ CO10) を User1 が購入する取引
        for ($i = 6; $i <= 10; $i++) {
            Transaction::create([
                'item_id' => $i,
                'buyer_id' => 1, // User1 が購入者
                'status' => 'negotiating', // 交渉中のステータス
            ]);
        }
    }
}
