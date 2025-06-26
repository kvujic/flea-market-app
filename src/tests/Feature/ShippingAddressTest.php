<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\services\PurchaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShippingAddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_address_reflected_on_purchase_screen()
    {
        $user = User::factory()->create();
        $user->profile()->create([
            'name' => 'テストユーザー',
            'postal_code' => '000-0000',
            'address' => '初期設定住所',
            'building' => '初期設定ビル',
        ]);

        $item = Item::factory()->create(['is_sold' => false]);

        $this->actingAs($user);

        $response = $this->post(route('purchase.address.update', $item->id), [
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '大阪府堺市１−１',
            'shipping_building' => 'test building',
        ]);
        $response->assertRedirect();

        $response = $this->get(route('purchase.purchase', $item->id));

        $response->assertStatus(200);
        $response->assertSee('123-4567');
        $response->assertSee('大阪府堺市１−１');
        $response->assertSee('test building');
    }

    public function test_shipping_address_saved_with_purchase()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false]);

        $this->actingAs($user);

        session([
            'shipping_postal_code' => '234-5678',
            'shipping_address' => '兵庫県神戸市２−２',
            'shipping_building' => 'sample building',
        ]);

        $result = app(PurchaseService::class)->complete(
            [
                'user_id' => $user->id,
                'item_id' => $item->id,
                'shipping_postal_code' => '234-5678',
                'shipping_address' => '兵庫県神戸市２−２',
                'shipping_building' => 'sample building',
            ],
            'カード支払い',
            'test_transaction_id_001',
            $item->price,
        );

        $this->assertTrue($result);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'shipping_postal_code' => '234-5678',
            'shipping_address' => '兵庫県神戸市２−２',
            'shipping_building' => 'sample building',
        ]);

    }
}
