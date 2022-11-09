<?php

namespace Database\Seeders\Post;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('post_statuses')->insert([
            [
                'title' => 'Черновик',
                'slug' => Str::slug('Черновик'),
                'sort' => 1,
            ],
            [
                'title' => 'Опубликован',
                'slug' => Str::slug('Опубликован'),
                'sort' => 2,
            ],
            [
                'title' => 'На модерации',
                'slug' => Str::slug('На модерации'),
                'sort' => 3,
            ],
            [
                'title' => 'Снят с публикации',
                'slug' => Str::slug('Снят с публикации'),
                'sort' => 4,
            ],
        ]);
    }
}
