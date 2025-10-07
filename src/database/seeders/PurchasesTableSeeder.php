<?php

namespace Database\Seeders;

use App\Models\Purchase;
use App\Models\Item;
use Illuminate\Database\Seeder;

class PurchasesTableSeeder extends Seeder
{
    public function run(): void
    {
        $purchases = [
            [
                'user_id' => 2,
                'item_id' => 4,
                'payment_method' => 'コンビニ支払い',
                'amount' => 4000,
                'shipping_postal_code' => '650-0042',
                'shipping_address' => '兵庫県神戸市中央区波止場町5−５',
            ],
            [
                'user_id' => 1,
                'item_id' => 9,
                'payment_method' => 'カード支払い',
                'amount' => 15000,
                'shipping_postal_code' => '556-0002',
                'shipping_address' => '大阪府大阪市浪速区恵美須東１丁目18−６',
            ],
        ];

        foreach($purchases as $purchase) {
            Purchase::create($purchase);
        }

        // change to sold
        foreach ([1, 9] as $itemId) {
            $item = Item::find($itemId);
            if ($item) {
                $item->is_sold = true;
                $item->save();
            }
        }
    }
}
