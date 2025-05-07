<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemCategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('item_category')->truncate(); // 初期化

        // 商品とカテゴリーの関連付け
        $itemCategories = [
            ['item_id' => 1, 'category_id' => 1], // 腕時計 -> ファッション
            ['item_id' => 2, 'category_id' => 2], // HDD -> 家電
            ['item_id' => 3, 'category_id' => 7], // 玉ねぎ3束 -> 本
            ['item_id' => 4, 'category_id' => 1], // 革靴 -> ファッション
            ['item_id' => 5, 'category_id' => 2], // ノートPC -> 家電
            ['item_id' => 6, 'category_id' => 9], // マイク -> スポーツ
            ['item_id' => 7, 'category_id' => 1], // ショルダーバッグ -> ファッション
            ['item_id' => 8, 'category_id' => 9], // タンブラー -> スポーツ
            ['item_id' => 9, 'category_id' => 12], // コーヒーミル -> アクセサリー
            ['item_id' => 10, 'category_id' => 13], // メイクセット -> おもちゃ
        ];

        DB::table('item_category')->insert($itemCategories);
    }
}
