<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 外部キー制約を一時的に無効化
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // itemsテーブルをリセット（既存データを削除してIDをリセット）
        DB::table('items')->truncate();

        // 外部キー制約を再度有効化
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 商品データの定義
        $items = [
            [
                'user_id' => 1,
                'name' => '腕時計',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'condition' => '良好',
                'brand' => 'EMPORIO ARMANI',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/armani-watch.jpg',
                'is_sold' => false,
            ],
            [
                'user_id' => 1,
                'name' => 'HDD',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'condition' => '良好',
                'brand' => 'Western Digital',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/hdd.jpg',
                'is_sold' => false,
            ],
            [
                'user_id' => 1,
                'name' => '玉ねぎ3束',
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
                'condition' => 'やや傷や汚れあり',
                'brand' => 'Local Farm',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/onion.jpg',
                'is_sold' => false,
            ],
            [
                'user_id' => 1,
                'name' => '革靴',
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'condition' => '状態が悪い',
                'brand' => 'LeatherCraft',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/shoes.jpg',
                'is_sold' => false,
            ],
            [
                'user_id' => 1,
                'name' => 'ノートPC',
                'description' => '高性能なノートパソコン',
                'price' => 45000,
                'condition' => '良好',
                'brand' => 'DELL',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/laptop.jpg',
                'is_sold' => false,
            ],
            [
                'user_id' => 2,
                'name' => 'マイク',
                'description' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'condition' => '良好',
                'brand' => 'SHURE',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/mic.jpg',
                'is_sold' => false,
            ],
            [
                'user_id' => 2,
                'name' => 'ショルダーバッグ',
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'condition' => 'やや傷や汚れあり',
                'brand' => 'GUCCI',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/bag.jpg',
                'is_sold' => false,
            ],
            [
                'user_id' => 2,
                'name' => 'コーヒーミル',
                'description' => '手動のコーヒーミル',
                'price' => 4000,
                'condition' => '良好',
                'brand' => 'Kalita',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/coffee-mill.jpg',
                'is_sold' => false,
            ],
            [
                'user_id' => 2,
                'name' => 'タンブラー',
                'description' => '使いやすいタンブラー',
                'price' => 500,
                'condition' => '良好',
                'brand' => 'Starbucks',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/tumbler.jpg',
                'is_sold' => false,
            ],
            [
                'user_id' => 2,
                'name' => 'メイクセット',
                'description' => '便利なメイクアップセット',
                'price' => 2500,
                'condition' => '良好',
                'brand' => 'SHISEIDO',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/makeup-set.jpg',
                'is_sold' => false,
            ],
        ];

        // 商品データの挿入
        Item::insert($items);
    }
}
