<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('statuses')->insert([
            [
                'key' => 'draft',
                'name' => 'Draft',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'verified',
                'name' => 'Verified',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
