<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;
use Database\Seeders\ConditionSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionSeeder::class);
    }
    public function test_updated_address_is_reflected_on_purchase_page()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        Profile::create([
            'user_id'     => $user->id,
            'name'        => 'テストユーザー',
            'postal_code' => '123-4567',
            'address'     => '東京都渋谷区',
            'building'    => 'テストビル',
        ]);
        $this->actingAs($user)->post(route('purchase.address.update', $item), [
            'postal_code' => '987-6543',
            'address'     => '大阪府大阪市',
            'building'    => '新テストビル',
        ]);
        $response = $this->actingAs($user)->get(route('purchase.create', $item));

        $response->assertStatus(200);
        $response->assertSee('987-6543');
        $response->assertSee('大阪府大阪市');
        $response->assertSee('新テストビル');
    }
    public function test_address_is_linked_to_purchase()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        Profile::create([
            'user_id'     => $user->id,
            'name'        => 'テストユーザー',
            'postal_code' => '123-4567',
            'address'     => '東京都渋谷区',
            'building'    => 'テストビル',
        ]);

        $this->actingAs($user)->post(route('purchase.address.update', $item), [
            'postal_code' => '987-6543',
            'address'     => '大阪府大阪市',
            'building'    => '新テストビル',
        ]);

        session([
            'payment_method' => 'credit_card',
            'postal_code'    => '987-6543',
            'address'        => '大阪府大阪市',
            'building'       => '新テストビル',
        ]);

        $this->actingAs($user)->get(route('purchase.success', $item));

        $this->assertDatabaseHas('purchases', [
            'buyer_id'       => $user->id,
            'item_id'        => $item->id,
            'payment_method' => 'credit_card',
            'status'         => 'completed',
            'postal_code'    => '987-6543',
            'address'        => '大阪府大阪市',
        ]);

        $this->assertDatabaseHas('profiles', [
            'user_id'     => $user->id,
            'postal_code' => '987-6543',
            'address'     => '大阪府大阪市',
        ]);
    }
}