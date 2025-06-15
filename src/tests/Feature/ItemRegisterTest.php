<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ItemRegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_register_item_with_valid_data()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $categories = Category::factory()->count(2)->create();
        $condition = Condition::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('sell'), [
            'name' => 'マルチカテゴリ商品',
            'description' => '複数のカテゴリに属する商品です',
            'price' => 9999,
            'condition_id' => $condition->id,
            'categories' => $categories->pluck('id')->toArray(),
            'item_image' => UploadedFile::fake()->image('dummy.jpeg'),
        ]);
        $response->dump();

        $response->assertRedirect(route('item.index'));

        $this->assertDatabaseHas('items', [
            'name' => 'マルチカテゴリ商品',
            'price' => 9999,
            'condition_id' => $condition->id,
            'user_id' => $user->id,
        ]);
        
        $item = Item::where('name', 'マルチカテゴリ商品')->first();
        Storage::disk('public')->assertExists($item->item_image);

        foreach($categories as $category) {
            $this->assertTrue($item->categories->contains($category));
        }
    }
}
