<?php

namespace Database\Factories\Post;

use App\Models\Post\Post;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $author = User::inRandomOrder()->first();

        return [
            'title' => $this->faker->sentence(5),
            'content' => $this->faker->text(3000),
            'author_id' => $author->id,
            'status_id' => 2,
            'created_at' => $this->faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'),
        ];
    }
}
