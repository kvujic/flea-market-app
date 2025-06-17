<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    public function run(): void
    {

        // create multiple categories

        $items = [
            [
                'user_id' => 1,
                'name' => '腕時計',
                'brand' => 'EMPORIO ARMANI',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'is_sold' => false,
                'condition_id' => 1,
                'item_image' => 'images/Watch.jpg',
            ],
            [
                'user_id' => 1,
                'name' => 'HDD',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'is_sold' => false,
                'condition_id' => 2,
                'item_image' => 'images/Disk.jpg',
            ],
            [
                'user_id' => 1,
                'name' => '玉ねぎ３束',
                'description' => '新鮮な玉ねぎ３束のセット',
                'price' => 300,
                'is_sold' => false,
                'condition_id' => 3,
                'item_image' => 'images/Onion.jpg',
            ],
            [
                'user_id' => 1,
                'name' => '革靴',
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'is_sold' => false,
                'condition_id' => 4,
                'item_image' => 'images/Shoes.jpg',
            ],
            [
                'user_id' => 1,
                'name' => 'ノートPC',
                'description' => '高性能なノートパソコン',
                'price' => 45000,
                'is_sold' => false,
                'condition_id' => 1,
                'item_image' => 'images/Laptop.jpg',
            ],
            [
                'user_id' => 2,
                'name' => 'マイク',
                'description' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'is_sold' => false,
                'condition_id' => 2,
                'item_image' => 'images/Mic.jpg',
            ],
            [
                'user_id' => 2,
                'name' => 'ショルダーバッグ',
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'is_sold' => false,
                'condition_id' => 3,
                'item_image' => 'images/Purse.jpg',
            ],
            [
                'user_id' => 2,
                'name' => 'タンブラー',
                'description' => '使いやすいタンブラー',
                'price' => 500,
                'is_sold' => false,
                'condition_id' => 4,
                'item_image' => 'images/Tumbler.jpg',
            ],
            [
                'user_id' => 2,
                'name' => 'コーヒーミル',
                'description' => '手動のコーヒーミル',
                'price' => 4000,
                'is_sold' => false,
                'condition_id' => 1,
                'item_image' => 'images/CoffeeGrinder.jpg',
            ],
            [
                'user_id' => 2,
                'name' => 'メイクセット',
                'description' => '便利なメイクアップセット',
                'price' => 2500,
                'is_sold' => false,
                'condition_id' => 2,
                'item_image' => 'images/Makeup.jpg',
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
