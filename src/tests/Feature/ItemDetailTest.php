<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Category;
use Database\Seeders\ConditionSeeder;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ItemDetailTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionSeeder::class);
        $this->seed(CategorySeeder::class);
    }
    public function test_item_detail_displays_all_information()
    {
        $seller = User::factory()->create();
        $commenter = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'name'        => 'テスト商品',
            'brand'       => 'テストブランド',
            'price'       => 1000,
            'description' => 'テスト商品の説明',
        ]);
        Like::create([
            'user_id' => $commenter->id,
            'item_id' => $item->id,
        ]);
        Comment::create([
            'user_id' => $commenter->id,
            'item_id' => $item->id,
            'body'    => 'テストコメント',
        ]);
        $item->categories()->attach([1, 2]);

        $response = $this->get(route('items.show', $item));

        $response->assertStatus(200);
        $response->assertSee('テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('1,000');
        $response->assertSee('テスト商品の説明');
        $response->assertSee('テストコメント');
        $response->assertSee('1'); // いいね数
    }
    public function test_item_detail_displays_multiple_categories()
    {
        $item = Item::factory()->create();

        $item->categories()->attach([1, 2, 3]);

        $response = $this->get(route('items.show', $item));

        $response->assertStatus(200);
        $response->assertViewHas('item', function ($item) {
            return $item->categories->count() === 3;
        });
    }
}