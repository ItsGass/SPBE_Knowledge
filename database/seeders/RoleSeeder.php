<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = DB::table('users')->where('role','super_admin')->first();
        $admin = DB::table('users')->where('role','admin')->first();
        $verifikator = DB::table('users')->where('role','verifikator')->first();

        if ($superAdmin) {
            DB::table('super_admins')->insert([
                'user_id' => $superAdmin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($admin) {
            DB::table('admins')->insert([
                'user_id' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($verifikator) {
            DB::table('verifikator_profiles')->insert([
                'user_id' => $verifikator->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
