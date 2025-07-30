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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->text('content');
            $table->json('images')->nullable();
            $table->morphs('commentable');
            $table->boolean('is_approved')->default(false);
            $table->softDeletes();
            $table->timestamps();
            $table->nestedSet();

            // index
            $table->index([\Illuminate\Support\Facades\DB::raw('content(191)')], 'content_prefix_index');
            $table->index(['commentable_id', 'commentable_type']);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
