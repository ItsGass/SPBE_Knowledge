<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KnowledgeSeeder extends Seeder
{
    public function run(): void
    {
        $user = DB::table('users')->first();
        $scope = DB::table('scopes')->where('name','Umum')->first();
        $status = DB::table('statuses')->where('key','verified')->first();
        $tag = DB::table('tags')->first();

        if (!$user || !$scope || !$status) return;

        $knowledgeId = DB::table('knowledge')->insertGetId([
            'title' => 'Contoh Knowledge Publik',
            'body' => 'Ini adalah contoh knowledge awal agar aplikasi langsung terlihat hidup.',
            'type' => 'pdf',
            'visibility' => 'public',
            'created_by' => $user->id,
            'scope_id' => $scope->id,
            'status_id' => $status->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($tag) {
            DB::table('knowledge_tags')->insert([
                'knowledge_id' => $knowledgeId,
                'tag_id' => $tag->id,
            ]);
        }
    }
}
