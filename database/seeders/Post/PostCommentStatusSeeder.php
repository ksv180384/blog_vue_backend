<?php

namespace Database\Seeders\Post;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostCommentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('post_comment_statuses')->insert([
            [
                'title' => 'Опубликован',
                'slug' => Str::slug('Опубликован'),
                'sort' => 1,
            ],
            [
                'title' => 'Скрыт',
                'slug' => Str::slug('Скрыт'),
                'sort' => 2,
            ],
            [
                'title' => 'Удален',
                'slug' => Str::slug('Удален'),
                'sort' => 3,
            ],
        ]);
    }
}
