<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProfileEditTest extends TestCase
{
    use DatabaseMigrations;
    public function test_profile_edit_displays_current_values()
    {
        $user = User::factory()->create();
        Profile::create([
            'user_id'       => $user->id,
            'name'          => 'テストユーザー',
            'postal_code'   => '123-4567',
            'address'       => '東京都渋谷区',
            'building'      => 'テストビル',
            'profile_image' => 'profile_images/test.jpg',
        ]);
        $response = $this->actingAs($user)->get(route('mypage.profile.edit'));
        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区');
        $response->assertSee('profile_images/test.jpg');
    }
}