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
        Schema::create('view_counts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('countable_id');
            $table->string('countable_type');
            $table->unsignedBigInteger('count')->default(0);
            // index
            $table->unique(['countable_id', 'countable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('view_counts');
    }
};
