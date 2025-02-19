<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentTest extends TestCase
{
    use RefreshDatabase; // テスト実行ごとにデータベースをリセット

    /** @test */
    public function ログイン済みのユーザーはコメントを送信できる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user) // ユーザーを認証
            ->post("/comments/{$item->id}", [
                'content' => 'これはテストコメントです。'
            ])
            ->assertStatus(302); // 成功時にリダイレクトすることを確認

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'これはテストコメントです。'
        ]);
    }

    /** @test */
    public function ログインしていないユーザーはコメントを送信できない()
    {
        $item = Item::factory()->create();

        $this->post("/comments/{$item->id}", [
                'content' => '未ログインユーザーのコメント'
            ])
            ->assertRedirect('/login'); // ログインページへリダイレクトされる

        $this->assertDatabaseMissing('comments', [
            'content' => '未ログインユーザーのコメント'
        ]);
    }

    /** @test */
    public function コメントが未入力の場合、バリデーションエラーになる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)
            ->post("/comments/{$item->id}", [
                'content' => '' // 空のコメント
            ])
            ->assertSessionHasErrors(['content']); // バリデーションエラーを確認
    }

    /** @test */
    public function コメントが255文字を超える場合、バリデーションエラーになる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)
            ->post("/comments/{$item->id}", [
                'content' => str_repeat('あ', 256) // 256文字のコメント
            ])
            ->assertSessionHasErrors(['content']); // バリデーションエラーを確認
    }
}
