<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('knowledge_views', function (Blueprint $table) {
            $table->foreign(['knowledge_id'], 'kv_knowledge_fk')->references(['id'])->on('knowledge')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['user_id'], 'kv_user_fk')->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('knowledge_views', function (Blueprint $table) {
            $table->dropForeign('kv_knowledge_fk');
            $table->dropForeign('kv_user_fk');
        });
    }
};
