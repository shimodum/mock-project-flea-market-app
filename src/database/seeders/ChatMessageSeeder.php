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
        // ユーザーを取得する
        $user = User::first();
        
        if (!$user) {
            // ユーザーが存在しない場合はエラーを出力して終了
            echo "⚠️  ユーザーが見つかりません。Seeder の実行順序を確認してください。";
            return;
        }

        // 1つ目のメッセージは未読状態
        ChatMessage::create([
            'transaction_id' => Transaction::first()->id,
            'user_id' => $user->id,
            'message' => 'こちらの商品の在庫はありますか？',
            'image_path' => null,
            'is_read' => false // 未読として登録
        ]);

        // 2つ目のメッセージは既読状態
        ChatMessage::create([
            'transaction_id' => Transaction::first()->id,
            'user_id' => $user->id,
            'message' => 'はい、まだ在庫があります。',
            'image_path' => null,
            'is_read' => true // 既読として登録
        ]);
    }
}
