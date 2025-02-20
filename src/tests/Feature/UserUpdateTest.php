<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // ユーザーを作成
        $this->user = User::factory()->create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
        ]);
    }

    /** @test */
    public function ユーザー情報更新画面が正しく表示される()
    {
        $response = $this->actingAs($this->user)
                         ->get('/mypage/profile');

        $response->assertStatus(200)
                 ->assertSee('プロフィール設定')
                 ->assertSee($this->user->name)
                 ->assertSee($this->user->email);
    }

    /** @test */
    public function ユーザー情報を更新できる()
    {
        $newData = [
            'name' => '新しいユーザー',
            'email' => 'new@example.com',
        ];

        $response = $this->actingAs($this->user)
                         ->put('/mypage/profile', $newData);

        $response->assertRedirect('/mypage/profile')
                 ->assertSessionHas('success', 'プロフィールが更新されました');

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => '新しいユーザー',
            'email' => 'new@example.com',
        ]);
    }

    /** @test */
    public function 必須項目が入力されていない場合はエラーメッセージを表示する()
    {
        $response = $this->actingAs($this->user)
                         ->put('/mypage/profile', [
                             'name' => '',
                             'email' => '',
                         ]);

        $response->assertSessionHasErrors(['name', 'email']);
    }

    /** @test */
    public function 無効なメールアドレスは更新できない()
    {
        $response = $this->actingAs($this->user)
                         ->put('/mypage/profile', [
                             'name' => '新しいユーザー',
                             'email' => 'invalid-email',
                         ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function 既に登録されているメールアドレスは使用できない()
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->actingAs($this->user)
                         ->put('/mypage/profile', [
                             'name' => '新しいユーザー',
                             'email' => 'existing@example.com',
                         ]);

        $response->assertSessionHasErrors(['email']);
    }
}
