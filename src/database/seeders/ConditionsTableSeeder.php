<?php

namespace Database\Seeders;

use App\Models\Condition;
use Illuminate\Database\Seeder;

class ConditionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $conditions = [
            '良好',
            '目立った傷や汚れなし',
            'やや傷や汚れあり',
            '状態が悪い',
        ];

        foreach ($conditions as $condition) {
            Condition::firstOrCreate(['condition' => $condition]);
        }

    }
}
