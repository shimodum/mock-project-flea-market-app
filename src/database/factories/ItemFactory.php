<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->text,
            'price' => $this->faker->randomNumber(4),
            'is_sold' => false, // 初期状態では未販売
            'condition' => 'new', // 🔹 condition のデフォルト値を追加
            'user_id' => \App\Models\User::factory(), // ランダムなユーザーを関連付け
        ];
    }
}
