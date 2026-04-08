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
        Schema::create('bots', function (Blueprint $table) {
            $table->id();
            $table->integer('baidu')->default(0);
            $table->integer('bing')->default(0);
            $table->integer('duckduckgo')->default(0);
            $table->integer('google')->default(0);
            $table->integer('yandex')->default(0);
            $table->integer('other')->default(0);
            $table->timestamps();

            $table->rawIndex('(DATE(created_at))', 'idx_created_at_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bots');
    }
};
