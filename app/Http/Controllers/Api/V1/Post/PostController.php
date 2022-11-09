<?php

namespace App\Http\Controllers\Api\V1\Post;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\UpdateRatingRequest;
use App\Http\Resources\Post\PostCollection;
use App\Http\Resources\Post\PostCommentCollection;
use App\Http\Resources\Post\PostPaginateCollection;
use App\Http\Resources\Post\PostResource;
use App\Services\PostCommentService;
use App\Services\PostService;
use Illuminate\Support\Facades\Auth;

class PostController extends BaseController
{
    /**
     * @var PostService
     */
    private $postService;

    /**
     * @var PostCommentService
     */
    private $postCommentService;

    public function __construct(
        PostService $postService,
        PostCommentService $postCommentService
    )
    {
        parent::__construct();

        $this->middleware('auth')->only(['myPosts', 'store', 'up', 'down']);

        $this->postService = $postService;
        $this->postCommentService = $postCommentService;
    }

    public function posts()
    {
        $posts = $this->postService->getPosts();

        return new PostCollection($posts);

    }

    public function myPosts()
    {
        $userId = Auth::id();

        $posts = $this->postService->getPostsByUserId($userId);

        return response()->json(['posts' => new PostPaginateCollection($posts)]);
    }

    public function topPosts()
    {
        $posts = $this->postService->getPostsTop();
        return response()->json(['posts' => new PostPaginateCollection($posts)]);
    }

    public function show($id)
    {
        $post = $this->postService->getById($id);
        $postComments = $this->postCommentService->commentsParentByPost($id);

        return response()->json([
            'post' => new PostResource($post),
            'comments' => new PostCommentCollection($postComments),
        ]);
    }

    /**
     * Добавляем пост
     * @param CreatePostRequest $request
     * @return PostResource
     */
    public function store(CreatePostRequest $request)
    {
        $post = $this->postService->create($request->validated());
        return new PostResource($post);
    }

    /**
     * Лайк (поднимает пост в рейтинге)
     * @param UpdateRatingRequest $request
     * @return PostResource
     */
    public function up(UpdateRatingRequest $request)
    {

        $post = $this->postService->up($request->id);

        return new PostResource($post);
    }

    /**
     * Дислайк (опускает пост в рейтинге)
     * @param UpdateRatingRequest $request
     * @return PostResource
     */
    public function down(UpdateRatingRequest $request)
    {

        $post = $this->postService->down($request->id);

        return new PostResource($post);
    }
}
