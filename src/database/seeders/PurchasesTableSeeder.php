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
                'user_id' => 3,
                'item_id' => 4,
                'payment_method' => 'コンビニ払い',
                'amount' => 4000,
                'shipping_postal_code' => '105-0011',
                'shipping_address' => '東京都港区芝公園４丁目2−８',
            ],
            [
                'user_id' => 3,
                'item_id' => 1,
                'payment_method' => 'カード支払い',
                'amount' => 15000,
                'shipping_postal_code' => '105-0011',
                'shipping_address' => '東京都港区芝公園４丁目2−８',
            ],
        ];

        foreach($purchases as $purchase) {
            Purchase::create($purchase);
        }

        // change to sold
        foreach ([1, 4] as $itemId) {
            $item = Item::find($itemId);
            if ($item) {
                $item->is_sold = true;
                $item->save();
            }
        }
    }
}
