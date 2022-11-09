<?php

namespace Database\Seeders\Post;

use App\Models\Post\PostComment;
use Illuminate\Database\Seeder;

class PostCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PostComment::factory(500)->create();
        PostComment::factory(7000)->create();
    }
}
