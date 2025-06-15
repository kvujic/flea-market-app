<?php

namespace Database\Seeders;

use App\Models\Purchase;
use App\Models\Item;
use Illuminate\Database\Seeder;

class PurchasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Purchase::create ([
                'user_id' => 3,
                'item_id' => 4,
                'payment_method' => 'コンビニ払い',
                'amount' => 4000,
                'shipping_postal_code' => '105-0011',
                'shipping_address' => '東京都港区芝公園４丁目2−８',
        ]);
        // change to sold
        $item = Item::find(4);
        if ($item) {
            $item->is_sold = true;
            $item->save();
        }
    }
}
