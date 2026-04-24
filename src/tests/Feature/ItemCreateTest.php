<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\ConditionSeeder;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItemCreateTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionSeeder::class);
        $this->seed(CategorySeeder::class);
    }
    public function test_item_can_be_created_with_all_information()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('sell.store'), [
            'name'         => 'テスト商品',
            'brand'        => 'テストブランド',
            'description'  => 'テスト商品の説明',
            'price'        => 1000,
            'condition_id' => 1,
            'category_id'  => [1, 2],
            'image'        => UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg'),
        ]);

        $this->assertDatabaseHas('items', [
            'name'         => 'テスト商品',
            'brand'        => 'テストブランド',
            'description'  => 'テスト商品の説明',
            'price'        => 1000,
            'condition_id' => 1,
            'user_id'      => $user->id,
        ]);
        $item = \App\Models\Item::where('name', 'テスト商品')->first();
        $this->assertEquals(2, $item->categories->count());

        $response->assertRedirect(route('items.index'));
    }
}