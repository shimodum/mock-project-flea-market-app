<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;

class ItemShowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate'); // DBをリセット
        $this->artisan('db:seed'); // シーダーを実行
    }

    /** @test */
    public function 商品詳細ページで必要な情報が表示される()
    {
        // 🔹 ユーザー作成
        $user = User::factory()->create();

        // 🔹 テストデータ作成（商品）
        $item = Item::factory()->create([
            'name' => '腕時計',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'price' => 15000,
            'condition' => 1,
            'user_id' => $user->id,
            'is_sold' => false,
        ]);

        // 🔹 商品にカテゴリを関連付け
        $category = Category::factory()->create(['name' => 'アクセサリー']);
        $item->categories()->attach($category);

        // 🔹 商品にコメントを追加
        Comment::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => '素敵な商品ですね！',
        ]);

        // 🔹 商品にいいねを追加
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 🔹 商品詳細ページにアクセス
        $response = $this->get('/item/' . $item->id);

        // 🔹 ステータスコード確認
        $response->assertStatus(200);

        // 🔹 商品名・価格・説明が表示されているか確認
        $response->assertSee($item->name);
        $response->assertSee(number_format($item->price));
        $response->assertSee($item->description);

        // 🔹 商品のカテゴリが表示されているか確認
        $response->assertSee($category->name);

        // 🔹 コメントが表示されているか確認
        $response->assertSee('素敵な商品ですね！');

        // 🔹 いいねボタンの表示確認
        $response->assertSee('☆');

        ob_end_clean(); // 出力バッファをクリア
    }
}
