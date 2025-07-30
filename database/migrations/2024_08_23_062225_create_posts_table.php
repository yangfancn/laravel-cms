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
            $table->unsignedBigInteger('category_id', false, true);
            $table->unsignedBigInteger('user_id', false, true)->nullable();
            $table->string('title', 255);
            $table->string('summary', 255)->nullable();
            $table->string('thumb')->nullable();
            $table->text('content');
            $table->string('original_url', 255)->nullable()->unique();
            $table->string('slug', 199)->nullable()->unique();
            $table->timestamps();
            // index
            $table->index(['category_id', 'created_at']);
            // foreign
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
