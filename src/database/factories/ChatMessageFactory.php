<?php

namespace Database\Factories;

use App\Models\ChatMessage;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatMessageFactory extends Factory
{
    protected $model = ChatMessage::class;

    public function definition()
    {
        return [
            'transaction_id' => Transaction::factory(),
            'user_id' => User::factory(),
            'message' => $this->faker->sentence,
            'is_read' => $this->faker->boolean(50), // 50%の確率で既読/未読
            'image_path' => null,
        ];
    }
}
