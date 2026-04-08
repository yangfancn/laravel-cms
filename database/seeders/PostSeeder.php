<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            ['name' => 'news', 'directory' => 'news', 'type' => 1],
            ['name' => 'wiki', 'directory' => 'wiki', 'type' => 1],
        ]);
        Post::factory()->count(100)->create();
    }
}
