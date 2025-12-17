<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScopeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('scopes')->insert([
            ['name' => 'Umum', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Desa', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Internal', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
