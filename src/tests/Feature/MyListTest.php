<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function いいねした商品のみが表示される()
    {
        // ユーザーを作成し、ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // 商品を2つ作成し、ユーザーがいいねをつける
        $items = Item::factory()->count(2)->create();
        foreach ($items as $item) {
            Like::factory()->create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
        }

        // マイリストを取得
        $response = $this->get('/?tab=mylist');

        // ステータスコードが 200 (OK) であることを確認
        $response->assertStatus(200);

        // いいねした商品が表示されていることを確認
        $response->assertSee($items[0]->name);
        $response->assertSee($items[1]->name);
    }

    /** @test */
    public function 購入済み商品には_sold_ラベルが表示される()
    {
        // ユーザーを作成し、ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // 購入済みの商品を作成
        $item = Item::factory()->create(['is_sold' => true]);
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // マイリストを取得
        $response = $this->get('/?tab=mylist');

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // Sold ラベルが表示されることを確認
        $response->assertSee('Sold');
    }

    /** @test */
    public function 自分が出品した商品はマイリストに表示されない()
    {
        // ユーザーを作成し、ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // 自分が出品した商品を作成
        $ownItem = Item::factory()->create(['user_id' => $user->id]);

        // いいねをつける
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $ownItem->id,
        ]);

        // マイリストを取得
        $response = $this->get('/?tab=mylist');

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // 自分の商品が表示されないことを確認
        $response->assertDontSee($ownItem->name);
    }

    /** @test */
    public function 未認証のユーザーはマイリストを取得できない()
    {
        // 未ログインの状態でマイリストを取得
        $response = $this->get('/?tab=mylist');

        // ログインページにリダイレクトされることを確認
        $response->assertRedirect('/login');
    }
}
