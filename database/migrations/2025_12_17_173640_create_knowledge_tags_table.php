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
        Schema::create('knowledge_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('knowledge_id')->index('kt_knowledge_idx');
            $table->unsignedBigInteger('tag_id')->index('kt_tag_idx');
            $table->timestamps();

            $table->unique(['knowledge_id', 'tag_id'], 'knowledge_tag_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_tags');
    }
};
