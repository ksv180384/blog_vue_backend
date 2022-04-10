<?php

namespace Database\Factories\Post;

use App\Models\Post\Post;
use App\Models\Post\PostComment;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostCommentFactory extends Factory
{
    protected $model = PostComment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $author = User::inRandomOrder()->first();
        $post = Post::inRandomOrder()->first();
        $comment = PostComment::inRandomOrder()->first();
        $addParent = rand(0, 1);

        if($addParent && !empty($comment)){
            $postId = $comment->post_id;
            $parentId = $comment->id;
            $branchId = !empty($comment->branch_id) ? $comment->branch_id : $comment->id;
        }else{
            $postId = $post->id;
            $parentId = null;
            $branchId = null;
        }

        return [
            'author_id' => $author->id,
            'post_id' => $postId,
            'parent_id' => $parentId,
            'branch_id' => $branchId,
            'comment' => $this->faker->text(100),
            'status_id' => 1,
            'created_at' => $this->faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'),
        ];
    }
}
