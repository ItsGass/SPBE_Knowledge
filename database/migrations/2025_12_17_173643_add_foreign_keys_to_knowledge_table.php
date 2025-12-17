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
        Schema::table('knowledge', function (Blueprint $table) {
            $table->foreign(['created_by'], 'knowledge_created_by_fk')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['scope_id'], 'knowledge_scope_id_fk')->references(['id'])->on('scopes')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['status_id'], 'knowledge_status_id_fk')->references(['id'])->on('statuses')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['verified_by'], 'knowledge_verified_by_fk')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('knowledge', function (Blueprint $table) {
            $table->dropForeign('knowledge_created_by_fk');
            $table->dropForeign('knowledge_scope_id_fk');
            $table->dropForeign('knowledge_status_id_fk');
            $table->dropForeign('knowledge_verified_by_fk');
        });
    }
};
