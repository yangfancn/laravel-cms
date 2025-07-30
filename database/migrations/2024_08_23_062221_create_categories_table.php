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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->string('directory', 80);
            $table->string('full_path')->nullable()->unique();
            $table->string('route', 255)->nullable();
            $table->boolean('show')->default(true);
            $table->boolean('type')->default(false)->comment('0:单页面，1:文章栏目');
            $table->integer('rank')->default(0);
            $table->nestedSet();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
