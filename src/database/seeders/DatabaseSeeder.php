<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\WeightLog;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // すでに登録されている場合は更新、なければ作成
        $user = User::updateOrCreate(
            ['email' => 'test@example.com'], // 検索条件
            [
                'name' => 'Test',
                'password' => bcrypt('password19980614'),
            ]
        );

        // そのユーザーに紐づく35件の体重ログを作成
        WeightLog::factory(35)->create([
            'user_id' => $user->id,
        ]);
    }
}
