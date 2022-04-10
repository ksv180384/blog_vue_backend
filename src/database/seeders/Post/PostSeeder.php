<?php

namespace Database\Seeders\Post;

use App\Models\Post\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::factory(300)->create();
    }
}
