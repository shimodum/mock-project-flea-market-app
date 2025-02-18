<?php

namespace Database\Factories;

use App\Models\Like;
use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
    protected $model = Like::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), // ユーザーを自動生成
            'item_id' => Item::factory(), // 商品を自動生成
        ];
    }
}
