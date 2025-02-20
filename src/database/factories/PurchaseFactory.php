<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Item;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(), // 新しいItemを関連付ける
            'payment_method' => $this->faker->randomElement(['カード支払い', 'コンビニ支払い']),
            'shipping_address' => $this->faker->streetAddress . "\n" . $this->faker->city . ', ' . $this->faker->state . ' ' . $this->faker->postcode,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
