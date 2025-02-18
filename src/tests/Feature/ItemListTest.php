<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 全商品を取得できる()
    {
        // テスト用商品を作成
        Item::factory()->count(5)->create();

        // 商品一覧取得
        $response = $this->get('/');

        // データが取得できることを確認
        $response->assertStatus(200);
        $response->assertSee(Item::first()->name);
    }

    /** @test */
    public function 購入済み商品は_Sold_と表示される()
    {
        // 購入済みの商品を作成
        $item = Item::factory()->create(['is_sold' => true]);

        // 商品一覧取得
        $response = $this->get('/');

        // 'Sold' ラベルが表示されることを確認
        $response->assertSee('Sold');
    }

    /** @test */
    public function 自分が出品した商品は表示されない()
    {
        // テスト用ユーザー作成
        $user = User::factory()->create();

        // ログイン状態にする
        $this->actingAs($user);

        // ログインユーザーが出品した商品を作成
        Item::factory()->create(['user_id' => $user->id]);

        // 商品一覧取得
        $response = $this->get('/');

        // 自分の商品が表示されないことを確認
        $response->assertDontSee(Item::first()->name);
    }
}
