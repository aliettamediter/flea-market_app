<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;
use Database\Seeders\ConditionSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionSeeder::class);
    }
    public function test_mypage_displays_user_information()
    {
        $user = User::factory()->create();
        Profile::create([
            'user_id' => $user->id,
            'name' => 'テストユーザー',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'profile_image' => 'profile_images/test.jpg',
        ]);
        $ownItem = Item::factory()->create(['user_id' => $user->id]);
        $otherItem = Item::factory()->create();
        Purchase::create([
            'buyer_id' => $user->id,
            'item_id' => $otherItem->id,
            'amount' => $otherItem->price,
            'payment_method' => 'credit_card',
            'status' => 'completed',
            'paid_at' => now(),
            'postal_code'    => '123-4567',
            'address'        => '東京都渋谷区',
            'building'       => null,
        ]);

        $response = $this->actingAs($user)->get('/mypage');
        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('profile_images/test.jpg');
        $response->assertViewHas('items', function ($items) use ($ownItem) {
            return $items->contains('id', $ownItem->id);
        });
        $response->assertViewHas('purchases', function ($purchases) use ($otherItem) {
            return $purchases->contains('item_id', $otherItem->id);
        });
    }
}
