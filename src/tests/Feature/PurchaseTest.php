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

    // 購入が完了する
    public function test_user_can_purchase_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // セッションに支払い方法をセット
        session(['payment_method' => 'credit_card']);

        $response = $this->actingAs($user)->get(route('purchase.success', $item));

        $this->assertDatabaseHas('purchases', [
            'buyer_id'       => $user->id,
            'item_id'        => $item->id,
            'payment_method' => 'credit_card',
            'status'         => 'completed',
        ]);

        $response->assertRedirect(route('items.index'));
    }

    // 購入した商品は「sold」と表示される
    public function test_purchased_item_displays_sold_label()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        session(['payment_method' => 'credit_card']);

        $this->actingAs($user)->get(route('purchase.success', $item));

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    // 購入した商品がプロフィールの購入した商品一覧に追加されている
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