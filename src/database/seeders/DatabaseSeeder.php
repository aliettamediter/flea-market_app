<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            ConditionSeeder::class,
            CategorySeeder::class,
            UserSeeder::class,
            ItemSeeder::class,
        ]);
    }
}
