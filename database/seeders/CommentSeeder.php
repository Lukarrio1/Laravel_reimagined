<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::all()->each(function ($post) {
            $faker = Factory::create();
            for ($i = 0; $i < 10; $i++) {
                Comment::create([
                    'comment' => $faker->text(20),
                    'post_id' => $post->id,
                    'user_id' => 1
                ]);
            }
        });
    }
}
