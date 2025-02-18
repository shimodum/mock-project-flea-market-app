<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate'); // 🔹 DBをリセット
        $this->artisan('db:seed'); // 🔹 必要ならシーダーを実行
    }

    /** @test */
    public function メールアドレスが未入力の場合バリデーションエラーが発生する()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function パスワードが未入力の場合バリデーションエラーが発生する()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function 未登録のメールアドレスを使用するとログインに失敗する()
    {
        $response = $this->post('/login', [
            'email' => 'notregistered@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function 正しい情報を入力するとログインできる()
    {
        // テスト用ユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(), // 🔹 メール認証済み
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/'); // 🔹 ログイン後のリダイレクト先を確認
    }

    /** @test */
    public function メール認証していないユーザーはログインできない()
    {
        // メール未認証のユーザーを作成
        $user = User::factory()->create([
            'email' => 'unverified@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => null, // 🔹 未認証
        ]);

        $response = $this->post('/login', [
            'email' => 'unverified@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest(); // 🔹 認証されていないことを確認
    }

    /** @test */
    public function ログアウトが成功する()
    {
        // ユーザーを作成しログイン
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user); // 🔹 ログイン状態にする

        $response = $this->post('/logout');

        $this->assertGuest(); // 🔹 ログアウト後に認証されていないことを確認
        $response->assertRedirect('/login'); // 🔹 ログアウト後のリダイレクト先を確認
    }
}
