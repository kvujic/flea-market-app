<?php

namespace Database\Seeders;

use App\Models\Like;
use Illuminate\Database\Seeder;

class LikesTableSeeder extends Seeder
{
    public function run(): void
    {
        $likes = [
            ['user_id' => 3, 'item_id' => 5],
            ['user_id' => 3, 'item_id' => 9],
        ];
        foreach($likes as $like)
        Like::create($like);
    }
}
