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
        // サンプルデータの挿入
        Transaction::create([
            'item_id' => Item::first()->id,
            'buyer_id' => User::first()->id,
            'status' => 'negotiating',
        ]);
    }
}
