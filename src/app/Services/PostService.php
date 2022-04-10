<?php

namespace App\Services;

use App\Models\Post\Post;
use App\Models\Post\PostUp;
use Illuminate\Support\Facades\Auth;

class PostService
{

    public function getById($id)
    {
        $userId = Auth::check() ? Auth::id() : 0;

        $post = Post::select([
            'posts.id',
            'posts.title',
            'posts.preview_img',
            'posts.preview',
            'posts.status_id',
            'posts.author_id',
            'posts.created_at',
            'posts.updated_at',
            'post_ups.up'
        ])
            ->leftJoin('post_ups', function($join) use ($userId) {
                $join->on('post_ups.post_id', '=', 'posts.id')->where('post_ups.user_id', '=', $userId);
            })
            ->with(['author:id,name,avatar', 'status:id,title'])
            ->withCount(['up', 'down'])
            ->find($id);

        return $post;
    }

    /**
     * Получаем посты для главной
     * @return mixed
     */
    public function getPosts()
    {

        $userId = Auth::check() ? Auth::id() : 0;

        $posts = Post::select([
            'posts.id',
            'posts.title',
            'posts.preview_img',
            'posts.preview',
            'posts.status_id',
            'posts.author_id',
            'posts.created_at',
            'posts.updated_at',
            'post_ups.up'
        ])
            ->leftJoin('post_ups', function($join) use ($userId) {
                $join->on('post_ups.post_id', '=', 'posts.id')->where('post_ups.user_id', '=', $userId);
            })
            ->with(['author:id,name,avatar', 'status:id,title', 'useUating'])
            ->withCount(['up', 'down'])
            ->paginate(5);

        return $posts;
    }

    /**
     * Лайк посту (поднимает пост в рейтинге)
     * @param $id
     * @return mixed
     */
    public function up($id)
    {
        $post = Post::findOrFail($id);
        $userUseRating = PostUp::where('user_id', Auth::id())->where('post_id', $post->id)->first();

        if($userUseRating){
            $userUseRating->delete();
        }else{
            $post->up()->create([
                'user_id' => Auth::id(),
                'up' => 1,
            ]);
        }

        $postUpdate = $this->getById($id);

        return $postUpdate;
    }

    /**
     * Дизлайк посту (опускаем пост в рейтинге)
     * @param $id
     * @return mixed
     */
    public function down($id)
    {
        $post = Post::findOrFail($id);
        $userUseRating = PostUp::where('user_id', Auth::id())->where('post_id', $post->id)->first();

        if($userUseRating){
            $userUseRating->delete();
        }else{
            $post->up()->create([
                'user_id' => Auth::id(),
                'up' => 2,
            ]);
        }

        $postUpdate = $this->getById($id);

        return $postUpdate;
    }
}
