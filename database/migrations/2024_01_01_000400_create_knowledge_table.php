<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('knowledge', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body')->nullable();

            $table->string('type')->default('pdf'); // pdf | image | video
            $table->string('thumbnail')->nullable();
            $table->string('attachment_path')->nullable();
            $table->string('youtube_url')->nullable();

            $table->enum('visibility', ['public','internal'])->default('internal');

            $table->foreignId('status_id')->constrained()->cascadeOnDelete();
            $table->foreignId('scope_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge');
    }
};
