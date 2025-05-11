<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatMessage;
use App\Models\Transaction;
use App\Models\User;

class ChatMessageSeeder extends Seeder
{
    public function run()
    {
        // 取引情報を取得
        $transaction = Transaction::first();
        
        if (!$transaction) {
            echo "⚠️  取引が見つかりません。Seeder の実行順序を確認してください。";
            return;
        }

        // 出品者と購入者を取得
        $buyer = User::find($transaction->buyer_id); // 購入者
        $seller = $transaction->item->user;          // 出品者

        // 1つ目のメッセージ（購入者が質問）
        ChatMessage::create([
            'transaction_id' => $transaction->id,
            'user_id' => $buyer->id, // 購入者が質問する
            'message' => 'こちらの商品の在庫はありますか？',
            'image_path' => null,
            'is_read' => false // 未読として登録
        ]);

        // 2つ目のメッセージ（出品者が回答）
        ChatMessage::create([
            'transaction_id' => $transaction->id,
            'user_id' => $seller->id, // 出品者が回答する
            'message' => 'はい、まだ在庫があります。',
            'image_path' => null,
            'is_read' => true // 既読として登録
        ]);
    }
}
