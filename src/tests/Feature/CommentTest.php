<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Database\Seeders\ConditionSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionSeeder::class);
    }
    public function test_authenticated_user_can_post_comment()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post(route('item.comments.store', $item), [
            'body' => 'テストコメント',
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'body'    => 'テストコメント',
        ]);

        $response->assertRedirect();
    }
    public function test_guest_cannot_post_comment()
    {
        $item = Item::factory()->create();

        $response = $this->post(route('item.comments.store', $item), [
            'body' => 'テストコメント',
        ]);

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'body'    => 'テストコメント',
        ]);

        $response->assertRedirect(route('login'));
    }
    public function test_comment_is_required()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post(route('item.comments.store', $item), [
            'body' => '',
        ]);

        $response->assertSessionHasErrors(['body']);
    }
    public function test_comment_cannot_exceed_255_characters()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post(route('item.comments.store', $item), [
            'body' => str_repeat('あ', 256),
        ]);

        $response->assertSessionHasErrors(['body']);
    }
}