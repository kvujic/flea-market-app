<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_an_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        // access to item page
        $response = $this->get(route('item.show', $item->id));
        $response->assertStatus(200);
        self::assertStringContainsString('liked-count">0</span>', $response->getContent());

        $this->post(route('item.like', $item->id));

        // check likes table
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // check the count
        $response = $this->get(route('item.show', $item->id));
        self::assertStringContainsString('liked-count">1</span>', $response->getContent());
    }

    public function test_Like_icon_changes_after_liking() {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)->post(route('item.like', $item->id));

        $response = $this->get(route('item.show', $item->id));

        $response->assertSee('images/star-color.svg');
        $response->assertDontSee('images/star.svg');
    }

    public function test_user_can_unlike_an_item() {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // liked item in advance
        $user->likedItems()->attach($item->id);

        $this->actingAs($user);

        // like unfollow
        $this->post(route('item.like', $item->id));

        // check the database
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get(route('item.show', $item->id));

        $response->assertSee('images/star.svg');
        $response->assertDontSee('images/star-color.svg');

    }
}
