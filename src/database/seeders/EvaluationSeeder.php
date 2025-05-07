<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evaluation;
use App\Models\Transaction;
use App\Models\User;

class EvaluationSeeder extends Seeder
{
    public function run()
    {
        Evaluation::create([
            'transaction_id' => Transaction::first()->id,
            'evaluator_id' => User::first()->id,
            'evaluatee_id' => User::find(2)->id, // 違うユーザーを指定
            'rating' => 5,
            'comment' => '迅速な対応、ありがとうございました。'
        ]);
    }
}
