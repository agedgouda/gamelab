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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->morphs('postable'); // Allows association with Game or Event models
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who created the post
            $table->enum('type', ['Review', 'Video', 'After Action Report', 'Strategy Guide']);
            $table->text('content'); // HTML formatted content
            $table->foreignId('parent_post_id')->nullable()->constrained('posts')->onDelete('cascade'); // For responses
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
