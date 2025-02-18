<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログアウトが成功する()
    {
        // テスト用ユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(), // メール認証済み
        ]);

        // ユーザーをログイン状態にする
        $this->actingAs($user);

        // ログアウトリクエストを送信
        $response = $this->post('/logout');

        // 認証されていないことを確認
        $this->assertGuest();

        // `/login` にリダイレクトされることを確認
        $response->assertRedirect('/login');
    }
}
