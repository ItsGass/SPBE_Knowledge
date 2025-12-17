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
        Schema::table('knowledge_comments', function (Blueprint $table) {
            $table->foreign(['knowledge_id'], 'kc_knowledge_fk')->references(['id'])->on('knowledge')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['user_id'], 'kc_user_fk')->references(['id'])->on('users')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('knowledge_comments', function (Blueprint $table) {
            $table->dropForeign('kc_knowledge_fk');
            $table->dropForeign('kc_user_fk');
        });
    }
};
