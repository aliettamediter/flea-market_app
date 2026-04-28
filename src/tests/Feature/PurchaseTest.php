<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Database\Seeders\ConditionSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionSeeder::class);
    }
    public function test_user_can_purchase_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $user->profile()->create([
            'name'        => 'テストユーザー',
            'postal_code' => '123-4567',
            'address'     => '東京都渋谷区',
        ]);

        session([
            'payment_method' => 'credit_card',
            'postal_code'    => '123-4567',
            'address'        => '東京都渋谷区',
            'building'       => '',
        ]);

        $response = $this->actingAs($user)->get(route('purchase.success', $item));

        $this->assertDatabaseHas('purchases', [
            'buyer_id'       => $user->id,
            'item_id'        => $item->id,
            'payment_method' => 'credit_card',
            'status'         => 'completed',
            'postal_code'    => '123-4567',
            'address'        => '東京都渋谷区',
        ]);

        $response->assertRedirect(route('items.index'));
    }
    public function test_purchased_item_displays_sold_label()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        session(['payment_method' => 'credit_card']);

        $this->actingAs($user)->get(route('purchase.success', $item));

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sold');
        session([
            'payment_method' => 'credit_card',
            'postal_code'    => '123-4567',
            'address'        => '東京都渋谷区',
            'building'       => '',
        ]);
    }
    public function test_purchased_item_is_added_to_mypage()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        Purchase::create([
            'buyer_id'       => $user->id,
            'item_id'        => $item->id,
            'amount'         => $item->price,
            'payment_method' => 'credit_card',
            'status'         => 'completed',
            'paid_at'        => now(),
        ]);

        $response = $this->actingAs($user)->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertViewHas('purchases', function ($purchases) use ($item) {
            return $purchases->contains('item_id', $item->id);
        });
    }
}