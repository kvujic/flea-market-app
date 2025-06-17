<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => '山田 太郎',
                'email' => 'taro@example.com',
                'password' => Hash::make('password1'),
                'email_verified_at' => now(),
            ],
            [
                'name' => '鈴木 花',
                'email' => 'hana@example.com',
                'password' => Hash::make('password2'),
                'email_verified_at' => now(),
            ],
            [
                'name' => '野原 ひろし',
                'email' => 'hiroshi@example.com',
                'password' => Hash::make('password3'),
                'email_verified_at' => now(),
            ],
            [
                'name' => '名無 権兵衛',
                'email' => 'gonbe@example.com',
                'password' => Hash::make('password4'),
                'email_verified_at' => now(),
            ],
            [
                'name' => '某 何某',
                'email' => 'nanigashi@example.com',
                'password' => Hash::make('password5'),
                'email_verified_at' => now(),
            ],
        ];

        foreach($users as $user) {
            User::create($user);
        }
    }
}
