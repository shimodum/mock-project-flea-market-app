<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class ShippingAddressTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $item;

    protected function setUp(): void
    {
        parent::setUp();

        // テストユーザー作成
        $this->user = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区道玄坂1-2-3',
            'address_building' => '渋谷ビル3F'
        ]);

        // テスト商品作成
        $this->item = Item::factory()->create([
            'is_sold' => false
        ]);
    }

    /** @test */
    public function 配送先が購入画面に表示される()
    {
        $response = $this->actingAs($this->user)
            ->get("/purchase/{$this->item->id}");

        $response->assertStatus(200)
            ->assertSee($this->user->postal_code)
            ->assertSee($this->user->address)
            ->assertSee($this->user->address_building);
    }

    /** @test */
    public function 配送先を変更すると反映される()
    {
        $newAddressData = [
            'postal_code' => '987-6543',
            'address' => '大阪府大阪市中央区心斎橋1-2-3',
            'address_building' => '心斎橋プラザ2F'
        ];

        // 先に購入履歴を作成する
        Purchase::create([
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'payment_method' => 'カード支払い',
            'shipping_address' => "{$this->user->postal_code} {$this->user->address} " .
                                ($this->user->address_building ? "({$this->user->address_building})" : "")
        ]);

        $response = $this->actingAs($this->user)
            ->post("/purchase/address/{$this->item->id}", $newAddressData);

        $response->assertRedirect("/purchase/address/{$this->item->id}");

        // `purchases` テーブルに `shipping_address` が更新されているか確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'shipping_address' => "{$newAddressData['postal_code']} {$newAddressData['address']} " .
                                ($newAddressData['address_building'] ? "({$newAddressData['address_building']})" : "")
        ]);
    }

}
