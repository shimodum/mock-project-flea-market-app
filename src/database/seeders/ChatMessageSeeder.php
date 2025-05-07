<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatMessage;
use App\Models\Transaction;

class ChatMessageSeeder extends Seeder
{
    public function run()
    {
        ChatMessage::create([
            'transaction_id' => Transaction::first()->id,
            'message' => 'こちらの商品の在庫はありますか？',
            'image_path' => null
        ]);

        ChatMessage::create([
            'transaction_id' => Transaction::first()->id,
            'message' => 'はい、まだ在庫があります。',
            'image_path' => null
        ]);
    }
}
