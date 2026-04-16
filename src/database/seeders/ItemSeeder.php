<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;
class ItemSeeder extends Seeder
{
    public function run()
    {
        $user = User::first() ?? User::factory()->create();
        Item::factory(40)
            ->for($user)
            ->create()
            ->each(function ($item) {
                $categoryIds = collect(range(1, 14))->random(rand(1, 3));
                $item->categories()->attach($categoryIds);
            });
    }
}