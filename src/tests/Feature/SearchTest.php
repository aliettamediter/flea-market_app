<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use Database\Seeders\ConditionSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionSeeder::class);
    }
    public function test_items_can_be_searched_by_name()
    {
        Item::factory()->create(['name' => '腕時計']);
        Item::factory()->create(['name' => 'ノートPC']);
        Item::factory()->create(['name' => '革靴']);

        $response = $this->get('/?search=腕');

        $response->assertViewHas('items', function ($items) {
            return $items->count() === 1
                && $items->first()->name === '腕時計';
        });
    }
    public function test_search_keyword_is_preserved_in_mylist()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['name' => '腕時計']);

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist&search=腕');

        $response->assertStatus(200);
        $response->assertViewHas('likedItems', function ($likedItems) {
            return $likedItems->contains('name', '腕時計');
        });
    }
}