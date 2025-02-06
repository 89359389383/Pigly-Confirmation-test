<?php

namespace Database\Factories;

use App\Models\WeightTarget;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeightTargetFactory extends Factory
{
    protected $model = WeightTarget::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), // ユーザーに紐づけ
            'target_weight' => $this->faker->randomFloat(1, 50, 60), // 50.0kg～60.0kgの範囲
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
