<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), // ユーザーファクトリから取得
            'item_id' => Item::factory(), // 商品ファクトリから取得
            'content' => $this->faker->sentence, // ランダムなコメント内容
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
