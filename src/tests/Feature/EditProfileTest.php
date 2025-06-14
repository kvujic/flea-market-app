<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EditProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_profile_displays_correct_information()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        $imageName = 'dummy.jpeg';
        $imagePath = 'profiles/' . $imageName;
        Storage::disk('public')->put($imagePath, 'dummy content');

        Profile::factory()->create([
            'user_id' => $user->id,
            'profile_image' => $imageName,
            'postal_code' => '123-4567',
            'address' => '和歌山県和歌山市９',
        ]);

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('123-4567');
        $response->assertSee('和歌山県和歌山市９');
        $response->assertSee('storage/profiles/' . $imageName);
    }
}
