<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
use App\Models\Condition;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // create multiple categories

        $user = User::factory()->create();

        $items = [
            [
                'data' => [
                    'user_id' => 1,
                    'name' => '腕時計',
                    'description' => 'スタイリッシュなデザインのメンズ腕時計',
                    'price' => 15000,
                    'is_sold' => true,
                    'condition_id' => 1,
                    'item_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                ],
                'categories' => [1, 5, 12],
            ],
            [
                'data' => [
                    'user_id' => 1,
                    'name' => 'HDD',
                    'description' => '高速で信頼性の高いハードディスク',
                    'price' => 5000,
                    'is_sold' => true,
                    'condition_id' => 2,
                    'item_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                ],
                'categories' => [2],
            ],
            [
                'data' => [
                    'user_id' => 1,
                    'name' => '玉ねぎ３束',
                    'description' => '新鮮な玉ねぎ３束のセット',
                    'price' => 300,
                    'is_sold' => false,
                    'condition_id' => 3,
                    'item_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                ],
                'categories' => [10],
            ],
            [
                'data' => [
                    'user_id' => 1,
                    'name' => '革靴',
                    'description' => 'クラシックなデザインの革靴',
                    'price' => 4000,
                    'is_sold' => false,
                    'condition_id' => 4,
                    'item_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                ],
                'categories' => [1, 5],
            ],
            [
                'data' => [
                    'user_id' => 1,
                    'name' => 'ノートPC',
                    'description' => '高性能なノートパソコン',
                    'price' => 45000,
                    'is_sold' => false,
                    'condition_id' => 1,
                    'item_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                ],
                'categories' => [2],
            ],
            [
                'data' => [
                    'user_id' => 2,
                    'name' => 'マイク',
                    'description' => '高音質のレコーディング用マイク',
                    'price' => 8000,
                    'is_sold' => false,
                    'condition_id' => 2,
                    'item_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                ],
                'categories' => [2],
            ],
            [
                'data' => [
                    'user_id' => 2,
                    'name' => 'ショルダーバッグ',
                    'description' => 'おしゃれなショルダーバッグ',
                    'price' => 3500,
                    'is_sold' => true,
                    'condition_id' => 3,
                    'item_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                ],
                'categories' => [1, 4],
            ],
            [
                'data' => [
                    'user_id' => 2,
                    'name' => 'タンブラー',
                    'description' => '使いやすいタンブラー',
                    'price' => 500,
                    'is_sold' => false,
                    'condition_id' => 4,
                    'item_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                ],
                'categories' => [10],
            ],
            [
                'data' => [
                    'user_id' => 2,
                    'name' => 'コーヒーミル',
                    'description' => '手動のコーヒーミル',
                    'price' => 4000,
                    'is_sold' => false,
                    'condition_id' => 1,
                    'item_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                ],
                'categories' => [10],
            ],
            [
                'data' => [
                    'user_id' => 2,
                    'name' => 'メイクセット',
                    'description' => '便利なメイクアップセット',
                    'price' => 2500,
                    'is_sold' => false,
                    'condition_id' => 2,
                    'item_image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                ],
                'categories' => [4, 6],
            ],
        ];

        foreach($items as $item) {
            $createdItem = Item::create($item['data']);
            $createdItem->categories()->attach($item['categories']);
        }

    }
}
