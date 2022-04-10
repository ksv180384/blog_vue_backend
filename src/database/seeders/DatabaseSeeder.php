<?php

namespace Database\Seeders;

use Database\Seeders\Post\PostCommentSeeder;
use Database\Seeders\Post\PostCommentStatusSeeder;
use Database\Seeders\Post\PostSeeder;
use Database\Seeders\Post\PostStatusSeeder;
use Database\Seeders\User\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            UserSeeder::class,
            PostStatusSeeder::class,
            PostSeeder::class,
            PostCommentStatusSeeder::class,
            PostCommentSeeder::class,
        ]);
    }
}
