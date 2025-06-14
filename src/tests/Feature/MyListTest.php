<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    public function test_displays_only_liked_items()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $likedItem = Item::factory()->create([
            'name'=> 'Liked Item',
            'user_id' => $otherUser->id,
        ]);
        $otherItem = Item::factory()->create([
            'name' => 'Not Liked',
            'user_id' => $otherUser->id,
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($user)->get(route('item.index', ['tab' => 'mylist']));

        $response->assertStatus(200);
        $response->assertSee('Liked Item');
        $response->assertDontSee('Not Liked');
    }

    public function test_sold_label_is_shown_for_purchase_items()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => true]);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get(route('item.index', ['tab' => 'mylist']));

        $response->assertStatus(200);
        $response->assertSee('sold');
    }

    public function test_own_items_are_not_shown_in_mylist()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $ownItem = Item::factory()->create(['user_id' => $user->id]);
        $likedItem = Item::factory()->create(['user_id' => $otherUser->id]);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $ownItem->id,
        ]);
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($user)->get(route('item.index', ['tab' => 'mylist']));

        $response->assertStatus(200);
        $response->assertSee($likedItem->name);
        $response->assertDontSee($ownItem->name);
    }

    public function test_guest_user_sees_nothing_in_mylist()
    {
        $response = $this->get(route('item.index', ['tab' => 'mylist']));
        $response->assertRedirect(route('login'));
    }
}
