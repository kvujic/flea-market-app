<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => 'テストユーザー',
            'postal_code' => '123-4567',
            'address' => '東京都新宿区',
            'building' => '新宿ビル１F',
            'profile_image' => 'profiles/test.jpeg',
        ];
    }
}
