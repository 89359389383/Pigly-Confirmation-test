<?php

namespace Database\Factories;

use App\Models\WeightLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeightLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), // ユーザーを自動で関連付け
            'date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'), // 過去1年間のランダムな日付
            'weight' => $this->faker->randomFloat(1, 50, 100), // 50.0kg〜100.0kgの範囲
            'calories' => $this->faker->numberBetween(1200, 3500), // 1200〜3500kcal
            'exercise_time' => $this->faker->time('H:i'), // ランダムな時間（例: 00:30）
            'exercise_content' => $this->faker->sentence(), // 簡単な文章
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
