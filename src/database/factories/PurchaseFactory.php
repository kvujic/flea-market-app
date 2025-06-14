<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
            'payment_method' => 'カード支払い',
            'amount' =>  $this->faker->numberBetween(1000, 5000),
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '大阪府淀川区',
            'shipping_building' => '淀川ビル',
            'stripe_transaction_id' => Str::uuid(),
        ];
    }
}
