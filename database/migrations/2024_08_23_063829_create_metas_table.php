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
        Schema::create('metas', function (Blueprint $table) {
            $table->id();
            $table->morphs('metable');
            $table->string('title', 255)->nullable();
            $table->string('keywords', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->json('others')->nullable();
            // index
            $table->unique(['metable_id', 'metable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metas');
    }
};
