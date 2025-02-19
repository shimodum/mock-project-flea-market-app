<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 支払い方法を変更すると反映される()
    {
        // テスト用ユーザー作成 & ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // 商品データ作成
        $item = Item::factory()->create([
            'is_sold' => false, // 未購入
        ]);

        // 支払い方法選択（例: クレジットカード）
        $response = $this->post("/purchase/{$item->id}", [
            'payment_method' => 'カード支払い',
        ]);

        // 購入情報がDBに保存されていることを確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'カード支払い',
        ]);

        // 変更が反映されたことを確認（購入画面へリダイレクト）
        $response->assertRedirect("/item/{$item->id}");
    }
}
