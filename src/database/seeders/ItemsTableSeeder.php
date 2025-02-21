<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 🔹 外部キー制約を一時的に無効化
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 🔹 itemsテーブルをリセット（既存データを削除してIDをリセット）
        DB::table('items')->truncate();

        // 🔹 外部キー制約を再度有効化
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 商品データを挿入
        DB::table('items')->insert([
            [
                'user_id' => 1,
                'name' => '腕時計',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'condition' => 1,
                'brand' => 'EMPORIO ARMANI',
                'image_path' => 'item_images/Armani+Mens+Clock.jpg',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'name' => 'HDD',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'condition' => 2,
                'brand' => '',
                'image_path' => 'item_images/HDD+Hard+Disk.jpg',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'name' => '玉ねぎ3束',
                'description' => '新鮮な玉ねぎ3本のセット',
                'price' => 300,
                'condition' => 3,
                'brand' => '',
                'image_path' => 'item_images/iLoveIMG+d.jpg',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'name' => '革靴',
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'condition' => 4,
                'brand' => '',
                'image_path' => 'item_images/Leather+Shoes+Product+Photo.jpg',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'name' => 'ノートPC',
                'description' => '高性能なノートパソコン',
                'price' => 45000,
                'condition' => 1,
                'brand' => '',
                'image_path' => 'item_images/Living+Room+Laptop.jpg',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6,
                'name' => 'マイク',
                'description' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'condition' => 2,
                'brand' => '',
                'image_path' => 'item_images/Music+Mic+4632231.jpg',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 7,
                'name' => 'ショルダーバッグ',
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'condition' => 3,
                'brand' => '',
                'image_path' => 'item_images/Purse+fashion+pocket.jpg',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 8,
                'name' => 'タンブラー',
                'description' => '使いやすいタンブラー',
                'price' => 500,
                'condition' => 4,
                'brand' => '',
                'image_path' => 'item_images/Tumbler+souvenir.jpg',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 9,
                'name' => 'コーヒーミル',
                'description' => '手動のコーヒーミル',
                'price' => 4000,
                'condition' => 1,
                'brand' => '',
                'image_path' => 'item_images/Waitress+with+Coffee+Grinder.jpg',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 10,
                'name' => 'メイクセット',
                'description' => '便利なメイクアップセット',
                'price' => 2500,
                'condition' => 2,
                'brand' => '',
                'image_path' => 'item_images/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
