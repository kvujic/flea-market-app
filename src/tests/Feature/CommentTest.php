<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_in_user_can_post_comment() {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('item.comment', ['item' => $item->id]), [
            'content' => 'これはテストコメントです',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'これはテストコメントです',
        ]);

        $this->assertEquals(1, $item->comments()->count());
    }

    public function test_guest_user_cannot_post_comment() {
        $item = Item::factory()->create();

        $response = $this->post(route('item.comment', ['item' => $item->id]), [
            'content' => 'ゲストのコメント',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('comments', [
            'content' => 'ゲストのコメント',
        ]);
    }

    public function test_requires_the_content_failed() {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('item.comment', ['item' => $item->id]), [
            'content' => '',
        ]);

        $response->assertSessionHasErrors([
            'content' => 'コメントを入力してください'
        ]);
    }

    public function test_content_must_be_less_than_256_characters() {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $longContent = str_repeat('あ', 256);

        $response = $this->post(route('item.comment', ['item' => $item->id]), [
            'content' => $longContent,
        ]);

        $response->assertSessionHasErrors([
            'content' => 'コメントは255文字以内で入力してください'
        ]);
    }
    
}
