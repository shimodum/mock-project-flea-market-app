<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class, // 先にユーザーを作成
            ItemsTableSeeder::class, // その後に商品を作成
            CategorySeeder::class,
            ItemCategorySeeder::class,
            TransactionSeeder::class,
            ChatMessageSeeder::class,
            EvaluationSeeder::class,
        ]);
    }
}
