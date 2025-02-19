<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function いいねアイコンを押すといいねした商品として登録される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)
            ->post("/like/{$item->id}")
            ->assertStatus(200);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /**
     * @test
     */
    public function 追加済みのアイコンが色が変化する()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get("/item/{$item->id}");

        ob_end_clean(); // 出力バッファをクリア

        $response->assertSee('like-button liked');
    }

    /**
     * @test
     */
    public function 再度いいねアイコンを押すといいねを解除できる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->actingAs($user)
            ->post("/like/{$item->id}")
            ->assertStatus(200);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /**
     * @test
     */
    public function いいね数が増減する()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // いいねを押す
        $this->actingAs($user)
            ->post("/like/{$item->id}")
            ->assertStatus(200);

        $this->assertEquals(1, Like::where('item_id', $item->id)->count());

        // いいねを解除
        $this->actingAs($user)
            ->post("/like/{$item->id}")
            ->assertStatus(200);

        $this->assertEquals(0, Like::where('item_id', $item->id)->count());
    }
}
