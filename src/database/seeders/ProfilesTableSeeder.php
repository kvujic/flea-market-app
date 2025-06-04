<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profiles = [
            [
                'user_id' => 1,
                'name' => '山田 太郎',
                'profile_image' => 'cat.jpeg',
                'postal_code' => '556-0002',
                'address' => '大阪府大阪市浪速区恵美須東１丁目18−６',
            ],
            [
                'user_id' => 2,
                'name' => '鈴木 花',
                'profile_image' => 'cocodemer.jpeg',
                'postal_code' => '650-0042',
                'address' => '兵庫県神戸市中央区波止場町5−５',
            ],
            [
                'user_id' => 3,
                'name' => '野原 ひろし',
                'profile_image' => 'donkey.jpeg',
                'postal_code' => '105-0011',
                'address' => '東京都港区芝浦公園４丁目2−８',
            ],
            [
                'user_id' => 4,
                'name' => '名無 権兵衛',
                'profile_image' => 'default-profile.svg',
                'postal_code' => '552-0022',
                'address' => '大阪府大阪市港区海岸通１丁目1−１０',
            ],
            [
                'user_id' => 5,
                'name' => '某 何某',
                'profile_image' => 'default-profile.svg',
                'postal_code' => '905-0206',
                'address' => '沖縄県国頭郡本部町石川42−４',
            ],
        ];
        foreach($profiles as $profile) {
            Profile::create($profile);
        }
    }
}
