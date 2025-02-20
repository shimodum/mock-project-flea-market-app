<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $listedItems;
    protected $purchasedItems;

    protected function setUp(): void
    {
        parent::setUp();

        // ユーザーを作成
        $this->user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => 'https://example.com/profile.jpg'
        ]);

        // ユーザーが出品した商品を作成
        $this->listedItems = Item::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'is_sold' => false
        ]);

        // 購入する商品を明示的に作成
        $purchasedItem1 = Item::factory()->create(['name' => '腕時計']);
        $purchasedItem2 = Item::factory()->create(['name' => 'タンブラー']);

        // ユーザーが購入した商品を作成（上で作成したアイテムに紐付ける）
        $this->purchasedItems = collect([
            Purchase::factory()->create([
                'user_id' => $this->user->id,
                'item_id' => $purchasedItem1->id,
            ]),
            Purchase::factory()->create([
                'user_id' => $this->user->id,
                'item_id' => $purchasedItem2->id,
            ]),
        ]);

    }

    /** @test */
    public function ユーザープロフィールページに必要な情報が表示される()
    {
        $response = $this->actingAs($this->user)
            ->get('/mypage');

        $response->assertStatus(200)
            ->assertSee($this->user->name)
            ->assertSee($this->user->profile_image);

        // 出品した商品が表示されるか
        foreach ($this->listedItems as $item) {
            $response->assertSee($item->name);
        }

        // 購入した商品が表示されるか
        foreach ($this->purchasedItems as $purchase) {
            $response->assertSee($purchase->item->name);
        }
    }
}
