<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Item;
use App\Models\User;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        // 外部キー制約を無効化
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // テーブルをクリア
        DB::table('transactions')->truncate();
        DB::table('chat_messages')->truncate();

        // 外部キー制約を再度有効化
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ユーザーとアイテムを取得
        $user1 = User::where('email', 'user1@example.com')->first();
        $user2 = User::where('email', 'user2@example.com')->first();

        $items1 = Item::whereBetween('id', [1, 5])->get(); // 商品CO01〜CO05
        $items2 = Item::whereBetween('id', [6, 10])->get(); // 商品CO06〜CO10

        // それぞれのユーザーに取引を追加
        foreach ($items1 as $item) {
            Transaction::create([
                'item_id' => $item->id,
                'buyer_id' => $user2->id, // User2が購入者
                'status' => 'negotiating',
            ]);
        }

        foreach ($items2 as $item) {
            Transaction::create([
                'item_id' => $item->id,
                'buyer_id' => $user1->id, // User1が購入者
                'status' => 'negotiating',
            ]);
        }
    }
}
