<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    protected $seller;
    protected $commenter;
    protected $condition;
    protected $categories;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seller = User::factory()->create(['name' => '出品者']);
        $this->commenter = User::factory()->create(['name' => 'コメントユーザー']);
        $this->condition = Condition::factory()->create(['condition' => '良好']);
        $this->categories = Category::factory()->count(3)->sequence(
            ['category' => '家電'],
            ['category' => 'インテリア'],
            ['category' => '本'],
        )->create();
    }

    public function test_item_detail_displays_full_information()
    {
        $item = Item::factory()->create([
            'user_id' => $this->seller->id,
            'item_image' => 'default.jpeg',
            'name' => 'test商品',
            'brand' => 'ブランド',
            'price' => '1500',
            'description' => '商品の説明',
            'condition_id' => $this->condition->id,
        ]);

        $item->categories()->attach($this->categories[0]->id);

        Like::factory()->count(2)->create(['item_id' => $item->id]);

        Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $this->commenter->id,
            'content' => '商品に関するコメント',
        ]);

        $response = $this->get(route('item.show', $item->id));

        $response->assertStatus(200)
        ->assertSee('出品者')
        ->assertSee('default.jpeg')
        ->assertSee('test商品')
        ->assertSee('ブランド')
        ->assertSee('1500')
        ->assertSee('商品の説明')
        ->assertSee('家電')
        ->assertSee('2') // like
        ->assertSee('1') // comment
        ->assertSee('コメントユーザー')
        ->assertSee('商品に関するコメント');
    }

    public function test_item_detail_displays_multiple_categories()
    {
        $item = Item::factory()->create([
            'user_id' => $this->seller->id,
            'name' => 'test商品',
            'description' => '商品の説明',
            'price' => '2000',
            'condition_id' => $this->condition->id,
        ]);

        $item->categories()->attach($this->categories->pluck('id'));

        $response = $this->get(route('item.show', $item->id));

        $response->assertStatus(200)
        ->assertSee('test商品')
        ->assertSee('家電')
        ->assertSee('インテリア')
        ->assertSee('本');
    }
}
