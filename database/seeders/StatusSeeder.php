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
                'label' => 'Draft',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'verified',
                'label' => 'Verified',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
