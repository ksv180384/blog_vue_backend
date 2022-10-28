<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Post\PostPaginateCollection;
use App\Services\PostService;

class IndexController extends BaseController
{
    /**
     * @var PostService
     */
    private $postService;

    public function __construct(PostService $postService)
    {
        parent::__construct();

        $this->postService = $postService;
    }

    public function index()
    {
        $posts = $this->postService->getPosts();

        return response()->json([
            'posts' => new PostPaginateCollection($posts),
        ]);
    }
}
