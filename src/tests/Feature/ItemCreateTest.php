<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Storage;

class ItemCreateTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // ユーザー作成
        $this->user = User::factory()->create();
    }

    /** @test */
    public function 商品出品画面が正しく表示される()
    {
        $response = $this->actingAs($this->user)->get('/sell');
        $response->assertStatus(200)
                 ->assertSee('商品を出品');
    }

    /** @test */
    public function 商品を正常に出品できる()
    {
        Storage::fake('public');
        $category = Category::factory()->create();
        
        $data = [
            'name' => 'テスト商品',
            'description' => 'これはテスト用の商品です。',
            'category_id' => $category->id,
            'status' => 'new',
            'price' => 1000,
            'image' => UploadedFile::fake()->image('test.jpg'),
        ];

        $response = $this->actingAs($this->user)->post('/sell', $data);
        
        $response->assertRedirect('/');
        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品',
        ]);
    }

    /** @test */
    public function 必須項目が入力されていない場合はエラーメッセージを表示する()
    {
        $response = $this->actingAs($this->user)->post('/sell', []);
        
        $response->assertSessionHasErrors(['name', 'description', 'category_id', 'status', 'price']);
    }

    /** @test */
    public function 無効なデータは登録できない()
    {
        $category = Category::factory()->create();
        
        $data = [
            'name' => '',
            'description' => str_repeat('a', 256),
            'category_id' => null,
            'status' => 'invalid_status',
            'price' => -100,
        ];

        $response = $this->actingAs($this->user)->post('/sell', $data);
        
        $response->assertSessionHasErrors(['name', 'description', 'category_id', 'status', 'price']);
    }

    /** @test */
    public function 画像のアップロードが正しく処理される()
    {
        Storage::fake('public');
        
        $category = Category::factory()->create();
        
        $data = [
            'name' => 'テスト商品',
            'description' => 'これはテスト用の商品です。',
            'category_id' => $category->id,
            'status' => 'new',
            'price' => 1000,
            'image' => UploadedFile::fake()->image('test.jpg'),
        ];

        $this->actingAs($this->user)->post('/sell', $data);
        
        Storage::disk('public')->assertExists('images/' . $data['image']->hashName());
    }

    /** @test */
    public function 適切なカテゴリが選択されていない場合はエラーメッセージを表示する()
    {
        $data = [
            'name' => 'テスト商品',
            'description' => 'これはテスト用の商品です。',
            'category_id' => null,
            'status' => 'new',
            'price' => 1000,
        ];

        $response = $this->actingAs($this->user)->post('/sell', $data);
        
        $response->assertSessionHasErrors(['category_id']);
    }
}
