<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_partial_item_name_returns_matching_results() {
        $user = User::factory()->create();

        Item::factory()->create(['name' => 'テレビ']);
        Item::factory()->create(['name' => 'スマートテレビ']);
        Item::factory()->create(['name' => 'HDD']);

        $response = $this->actingAs($user)->get(route('item.index', ['keyword' => 'テレビ']));

        $response->assertStatus(200);
        $response->assertSee('テレビ');
        $response->assertSee('スマートテレビ');
        $response->assertDontSee('HDD');
    }

    public function test_search_keyword_is_preserved_when_switching_to_mylist_tab() {
        $user = User::factory()->create();

        $likedItem = Item::factory()->create(['name' => 'スマートテレビ']);
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        // access on the home screen
        $responseHome = $this->actingAs($user)->get(route('item.index', ['keyword' => 'テレビ']));

        $responseHome->assertStatus(200);
        $responseHome->assertSee('テレビ');

        // transition to mylist tab
        $responseMylist = $this->actingAs($user)->get(route('item.index', ['tab' => 'mylist', 'keyword' => 'テレビ']));

        $responseMylist->assertStatus(200);
        $responseMylist->assertSee('テレビ');
        $responseMylist->assertSee('スマートテレビ');
    }
}
