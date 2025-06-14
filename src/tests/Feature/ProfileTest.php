<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $sellingItems;
    protected $boughtItem;

    public function setUp(): void
    {
        parent::setUp();

        // user
        $this->user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        Profile::factory()->create([
            'user_id' => $this->user->id,
            'profile_image' => 'profiles/default-image.png',
        ]);

        // selling items
        $this->sellingItems = Item::factory()->count(2)->create([
            'user_id' => $this->user->id,
        ]);

        // bought item from another user
        $seller = User::factory()->create();
        $this->boughtItem = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        Purchase::factory()->create([
            'user_id' => $this->user->id,
            'item_id' => $this->boughtItem->id,
        ]);

    }

    public function test_user_profile_displays_correct_information()
    {

        $response = $this->actingAs($this->user)->get(route('profile.index'));
        $response->assertStatus(200);
        $response->assertSee($this->user->name);

        $response = $this->actingAs($this->user)->get(route('profile.index', ['tab' => 'sell']));
        $response->assertStatus(200);
        $response->assertSee('profiles/default-image.png');
        foreach($this->sellingItems as $item) {
            $response->assertSee($item->name);
        }

        $response = $this->actingAs($this->user)->get(route('profile.index', ['tab' => 'buy']));
        $response->assertStatus(200);
        $response->assertSee($this->boughtItem->name);
    }
}
