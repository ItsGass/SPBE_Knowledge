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
        Schema::create('knowledge', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('body');
            $table->enum('type', ['pdf', 'image', 'video'])->default('pdf');
            $table->string('youtube_url')->nullable();
            $table->string('thumbnail')->nullable();
            $table->unsignedBigInteger('scope_id')->nullable()->index('knowledge_scope_id_fk');
            $table->unsignedBigInteger('status_id')->index('knowledge_status_id_fk');
            $table->string('attachment_path')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index('idx_knowledge_created_by');
            $table->unsignedBigInteger('verified_by')->nullable()->index('idx_knowledge_verified_by');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->enum('visibility', ['public', 'internal'])->default('internal');
            $table->decimal('average_rating', 3)->nullable()->default(0);
            $table->unsignedInteger('rating_count')->nullable()->default(0);
            $table->unsignedBigInteger('view_count')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge');
    }
};
