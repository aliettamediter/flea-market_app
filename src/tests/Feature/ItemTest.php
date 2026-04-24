<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Database\Seeders\ConditionSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionSeeder::class);
    }
    public function test_all_items_are_displayed()
    {
        Item::factory(5)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('items', function ($items) {
            return $items->count() === 5;
        });
    }
    public function test_sold_item_displays_sold_label()
    {
        Item::factory()->sold()->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }
    public function test_own_items_are_not_displayed()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $ownItem = Item::factory()->create(['user_id' => $user->id]);
        $otherItem = Item::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get('/');

        $response->assertViewHas('items', function ($items) use ($ownItem) {
            return $items->every(fn($item) => $item->id !== $ownItem->id);
        });
    }
}