<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class CategoryItemTableSeeder extends Seeder
{

    public function run(): void
    {
        $categoryItems = [
            1 => [1, 5, 12],
            2 => [2],
            3 => [10],
            4 => [1, 5],
            5 => [2],
            6 => [2],
            7 => [1, 4],
            8 => [10],
            9 => [10],
            10 => [6],
        ];

        foreach ($categoryItems as $itemId => $categoryIds) {
            $item = Item::find($itemId);

            if ($item) {
                $item->categories()->syncWithoutDetaching($categoryIds);
            }
        }
    }
}
