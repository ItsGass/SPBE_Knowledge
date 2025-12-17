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
        Schema::table('knowledge_tags', function (Blueprint $table) {
            $table->foreign(['knowledge_id'], 'kt_knowledge_fk')->references(['id'])->on('knowledge')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['tag_id'], 'kt_tag_fk')->references(['id'])->on('tags')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('knowledge_tags', function (Blueprint $table) {
            $table->dropForeign('kt_knowledge_fk');
            $table->dropForeign('kt_tag_fk');
        });
    }
};
