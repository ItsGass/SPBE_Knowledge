<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1. MASTER STATUS (Wajib ID-nya sama persis dengan dump)
        DB::table('statuses')->insertOrIgnore([
            ['id' => 1, 'key' => 'draft', 'label' => 'Draft', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'key' => 'verified', 'label' => 'Verified', 'created_at' => $now, 'updated_at' => $now],
        ]);

       

      
    
        // 4. AKUN SUPER ADMIN (Kunci Rumah)
        // Saya reset ID=2 biar konsisten, password jadi 'password'
        DB::table('users')->insertOrIgnore([
            'id' => 2, 
            'name' => 'Super Admins', 
            'email' => 'super@admin.com', 
            'role' => 'super_admin', 
            'password' => Hash::make('password'), // Password saya ubah biar tau
            'created_at' => $now, 
            'updated_at' => $now
        ]);

        // 5. RELASI SUPER ADMIN
        DB::table('super_admins')->insertOrIgnore([
            'user_id' => 2, // Konek ke user id 2 di atas
            'notes' => 'Seeder initial super admin',
            'created_at' => $now,
            'updated_at' => $now
        ]);
        
        $this->command->info('Starter Pack Berhasil! Login: super@admin.com | Pass: password');
    }
}