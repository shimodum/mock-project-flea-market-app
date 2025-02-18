<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品名で部分一致検索ができる()
    {
        // テスト用の商品を作成
        Item::factory()->create(['name' => 'テスト商品A']);
        Item::factory()->create(['name' => 'サンプル商品B']);
        Item::factory()->create(['name' => '商品C']);

        // 検索リクエストを実行
        $response = $this->get('/?query=テスト');

        // ステータスコードを確認
        $response->assertStatus(200);

        // 検索結果に含まれていることを確認
        $response->assertSee('テスト商品A');

        // 検索結果に含まれていないことを確認
        $response->assertDontSee('サンプル商品B');
        $response->assertDontSee('商品C');
    }

    /** @test */
    public function 検索結果がマイリストでも反映される()
    {
        // ユーザーを作成
        $user = User::factory()->create();

        // 商品を作成
        $item1 = Item::factory()->create(['name' => 'お気に入り商品X']);
        $item2 = Item::factory()->create(['name' => 'その他の商品Y']);

        // いいね登録
        Like::factory()->create(['user_id' => $user->id, 'item_id' => $item1->id]);

        // ユーザーとしてログイン
        $this->actingAs($user);

        // マイリスト検索リクエストを実行
        $response = $this->get('/?tab=mylist&query=お気に入り');

        // ステータスコードを確認
        $response->assertStatus(200);

        // マイリスト内で検索結果に含まれていることを確認
        $response->assertSee('お気に入り商品X');

        // 検索結果に含まれていないことを確認
        $response->assertDontSee('その他の商品Y');
    }
}
