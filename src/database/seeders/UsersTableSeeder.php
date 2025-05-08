<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // ダミーユーザー1: 商品CO01 ~ CO05 を出品するユーザー
        User::factory()->create([
            'name' => 'User1',
            'email' => 'user1@example.com',
            'password' => Hash::make('password1'),
            'postal_code' => '111-1111',
            'address' => '東京都港区六本木1-1-1',
            'address_building' => '六本木ヒルズ',
            'profile_image' => 'profile_images/profile_icon_1.png',
            'email_verified_at' => Carbon::now(), // 認証済み
        ]);

        // ダミーユーザー2: 商品CO06 ~ CO10 を出品するユーザー
        User::factory()->create([
            'name' => 'User2',
            'email' => 'user2@example.com',
            'password' => Hash::make('password2'),
            'postal_code' => '222-2222',
            'address' => '東京都渋谷区恵比寿2-2-2',
            'address_building' => '恵比寿タワー',
            'profile_image' => 'profile_images/profile_icon_7.png',
            'email_verified_at' => Carbon::now(), // 認証済み
        ]);

        // ダミーユーザー3: 紐づけのないユーザー
        User::factory()->create([
            'name' => 'User3',
            'email' => 'user3@example.com',
            'password' => Hash::make('password3'),
            'postal_code' => '333-3333',
            'address' => '東京都港区虎ノ門3-3-3',
            'address_building' => '虎ノ門ビル',
            'profile_image' => 'profile_images/profile_icon_14.png',
            'email_verified_at' => Carbon::now(), // 認証済み
        ]);
    }
}
