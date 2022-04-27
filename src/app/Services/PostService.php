<?php

namespace App\Services;

use App\Models\Post\Post;
use App\Models\Post\PostImage;
use App\Models\Post\PostUp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

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
            ->paginate(10);

        return $posts;
    }

    /**
     * @param array $post
     */
    public function create($postData)
    {
        $maxSize = 1024;

        $post = Post::create([
            'title' => $postData['title'],
            'content' => $postData['content'],
            'status_id' => 2,
            'author_id' => Auth::id(),
        ]);

        if(!empty($postData['images'])){
            foreach ($postData['images'] as $img) {
                $png_url = Str::random(10) . '.png';
                $path = storage_path('app/public/posts') . '/' . $png_url;

                $image = Image::make(file_get_contents($img['src']));

                if($image->width() > $maxSize){
                    $image->resize($maxSize, null, function($constraint) {
                        $constraint->aspectRatio();
                    })->save($path);
                }else{
                    $image->save($path);
                }

                PostImage::create([
                    'post_id' => $post->id,
                    'path' => $png_url,
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
