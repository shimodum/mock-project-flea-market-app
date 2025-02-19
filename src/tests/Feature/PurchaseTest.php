<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 購入処理が完了する()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false]);

        // ユーザーを認証状態にする
        $this->actingAs($user);

        // Stripe決済リクエストを送信
        $response = $this->post('/stripe/checkout', [
            'item_id' => $item->id,
            'payment_method' => 'card',
        ]);

        // JSONレスポンスでURLが返されることを確認
        $response->assertJsonStructure(['url']);

        // 決済成功処理をシミュレート
        $this->get("/purchase/success/{$item->id}");

        // DBで商品が売り切れになっていることを確認
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'is_sold' => true
        ]);
    }

    /** @test */
    public function 購入した商品は商品一覧画面で_sold_と表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false]);

        // ユーザーを認証状態にする
        $this->actingAs($user);

        // Stripe決済を実行
        $this->post('/stripe/checkout', [
            'item_id' => $item->id,
            'payment_method' => 'card',
        ]);

        // 成功画面をシミュレート
        $this->get("/purchase/success/{$item->id}");

        // 商品一覧画面を取得
        $response = $this->get('/');

        // 商品がSoldとして表示されていることを確認
        $response->assertSee('Sold');
    }

    /** @test */
    public function 購入した商品がプロフィールの購入した商品一覧に追加される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false]);

        // ユーザーを認証状態にする
        $this->actingAs($user);

        // Stripe決済を実行
        $this->post('/stripe/checkout', [
            'item_id' => $item->id,
            'payment_method' => 'card',
        ]);

        // 成功画面をシミュレート
        $this->get("/purchase/success/{$item->id}");

        // マイページ（購入履歴）を取得
        $response = $this->get('/mypage?tab=buy');

        // 商品が表示されていることを確認
        $response->assertSee($item->name);
    }
}
