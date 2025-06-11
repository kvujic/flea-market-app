<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_all_items() {
        $items = Item::factory()->count(3)->sequence(
            ['name' => 'Guest Item 1'],
            ['name' => 'Guest Item 2'],
            ['name' => 'Guest Item 3'],
        )->create();

        $response = $this->get('/');

        $response->assertStatus(200);

        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    public function test_displays_sold_label_for_sold_items() {
        $user = User::factory()->create();
        $this->actingAs($user);

        $soldItem = Item::factory()->create([
            'is_sold' => 'true',
            'name' => 'Sold Item',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sold Item');
        $response->assertSee('SOLD');
    }

    public function test_displays_all_items_except_my_own() {
        $user = User::factory()->create();
        $this->actingAs($user);

        // own item
        Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'My Item',
        ]);

        // others
        $otherItem = Item::factory()->create([
            'name' => 'Other Item',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Other Item');
        $response->assertDontSee('My Item');
    }
}
