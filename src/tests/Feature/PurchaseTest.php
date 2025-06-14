<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\services\PurchaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_purchase_item() {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false]);

        $this->actingAs($user);

        $service = app(PurchaseService::class);

        $result = $service->complete([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '大阪府堺市１−１',
            'shipping_building' => 'test building',
        ], 'カード支払い', 'txn_test_123', $item->price);

        $this->assertTrue($result);

        $this->assertDatabaseHas('purchases' , [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'カード支払い',
            'stripe_transaction_id' => 'txn_test_123',
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'is_sold' => true,
        ]);
    }

    public function test_purchased_item_shows_sold_label_on_item_list() {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false]);

        $this->actingAs($user);

        app(PurchaseService::class)->complete([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '大阪府堺市１−１',
            'shipping_building' => 'test building',
        ], 'カード支払い', 'txn_test_123', $item->price);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'is_sold' => true,
        ]);

        $response = $this->get(route('item.index'));

        $response->assertSee('SOLD');
    }

    public function test_purchased_item_appears_in_profile_purchased_list() {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false]);

        $this->actingAs($user);

        app(PurchaseService::class)->complete([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '大阪府堺市１−１',
            'shipping_building' => 'test building',
        ], 'カード支払い', 'txn_test_123', $item->price);


        $response = $this->get(route('profile.index', ['tab' => 'buy']));

        $response->assertSee($item->name);
    }
}
