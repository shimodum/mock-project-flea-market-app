<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'item_id' => Item::factory(), // アイテムのファクトリを使う
            'buyer_id' => User::factory(), // ユーザーのファクトリを使う
            'status' => $this->faker->randomElement(['negotiating', 'completed', 'canceled']),
        ];
    }
}
