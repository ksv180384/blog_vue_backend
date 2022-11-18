<?php

namespace App\Services;

use App\Models\Post\Post;
use App\Models\Post\PostImage;
use App\Models\Post\PostUp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PostService
{

    const PAGINATE_OFFSET = 6;

    /**
     * Получаем пост
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        $userId = Auth::check() ? Auth::id() : 0;

        $post = Post::query()
            ->select([
                'posts.id',
                'posts.title',
                'posts.content',
                'posts.status_id',
                'posts.author_id',
                'posts.created_at',
                'posts.updated_at',
                'post_ups.up'
            ])
            ->leftJoin('post_ups', function($join) use ($userId) {
                $join->on('post_ups.post_id', '=', 'posts.id')->where('post_ups.user_id', '=', $userId);
            })
            ->with(['author:id,name,avatar', 'status:id,title', 'images:id,post_id,path'])
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
        $posts = Post::query()
            ->postsList()
            ->orderByDesc('created_at')
            ->paginate(self::PAGINATE_OFFSET);

        return $posts;
    }

    /**
     * Посты пользователя
     * @param $userId
     * @return mixed
     */
    public function getPostsByUserId($userId){
        $posts = Post::query()
            ->postsList()
            ->orderByDesc('created_at')
            ->where('posts.author_id', $userId)
            ->paginate(self::PAGINATE_OFFSET);

        return $posts;
    }

    /**
     * Получаем посты с наибольшим рейтингом
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPostsTop()
    {
        $posts = Post::query()
            ->postsList()
            ->orderByDesc('rating')
            ->paginate(self::PAGINATE_OFFSET);

        return $posts;
    }


    /**
     * Добавить новый пост
     * @param array $post
     */
    public function create($postData)
    {
        $maxWidth = 1024;

        $post = Post::query()
            ->create([
                'title' => $postData['title'],
                'content' => $postData['content'],
                'status_id' => $postData['status_id'],
                'author_id' => Auth::id(),
            ]);

        if(!empty($postData['images'])){
            foreach ($postData['images'] as $img) {
                $file_img = Str::random(10) . '.' . $img->getClientOriginalExtension();
                $catalog = storage_path('app/public/posts');
                Storage::disk('public')->makeDirectory('posts');
                $path = $catalog . '/' . $file_img;

                $image = Image::make($img);

                if($image->width() > $maxWidth){
                    $image->resize($maxWidth, null, function($constraint) {
                        $constraint->aspectRatio();
                    })->save($path);
                }else{
                    $image->save($path);
                }

                PostImage::create([
                    'post_id' => $post->id,
                    'path' => $file_img,
                ]);
            }
        }

        return $post;
    }

    /**
     * Лайк посту (поднимает пост в рейтинге)
     * @param $id
     * @return mixed
     */
    public function up($id)
    {
        $post = Post::query()->findOrFail($id);
        $userUseRating = PostUp::query()->where('user_id', Auth::id())->where('post_id', $post->id)->first();

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
        $post = Post::query()->findOrFail($id);
        $userUseRating = PostUp::query()->where('user_id', Auth::id())->where('post_id', $post->id)->first();

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
