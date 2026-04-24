<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use Database\Seeders\ConditionSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MylistTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionSeeder::class);
    }
    public function test_only_liked_items_are_displayed()
    {
        $user = User::factory()->create();
        $likedItem = Item::factory()->create();
        $notLikedItem = Item::factory()->create();

        Like::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertViewHas('likedItems', function ($likedItems) use ($likedItem, $notLikedItem) {
            return $likedItems->contains('id', $likedItem->id)
                && !$likedItems->contains('id', $notLikedItem->id);
        });
    }
    public function test_sold_item_displays_sold_label()
    {
        $user = User::factory()->create();
        $soldItem = Item::factory()->sold()->create();

        Like::create([
            'user_id' => $user->id,
            'item_id' => $soldItem->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }
    public function test_guest_cannot_see_mylist()
    {
        Item::factory(3)->create();

        $response = $this->get('/?tab=mylist');

        $response->assertViewHas('likedItems', function ($likedItems) {
            return $likedItems->isEmpty();
        });
    }
}