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
        Schema::table('knowledge_ratings', function (Blueprint $table) {
            $table->foreign(['knowledge_id'], 'kr_knowledge_fk')->references(['id'])->on('knowledge')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['user_id'], 'kr_user_fk')->references(['id'])->on('users')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('knowledge_ratings', function (Blueprint $table) {
            $table->dropForeign('kr_knowledge_fk');
            $table->dropForeign('kr_user_fk');
        });
    }
};
