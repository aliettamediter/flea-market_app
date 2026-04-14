<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('conditions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('conditions')->insert([
            ['name' => '良好'],
            ['name' => '目立った傷や汚れなし'],
            ['name' => 'やや傷や汚れあり'],
            ['name' => '状態が悪い'],
        ]);
    }
    }
