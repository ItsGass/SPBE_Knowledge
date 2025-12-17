<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            StatusSeeder::class,
            ScopeSeeder::class,
            UserSeeder::class,
            RoleSeeder::class,
            TagSeeder::class,
            KnowledgeSeeder::class,
        ]);
    }
}
