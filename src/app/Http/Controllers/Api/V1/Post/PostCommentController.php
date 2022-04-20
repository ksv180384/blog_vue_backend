<?php

namespace App\Http\Controllers\Api\V1\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\CreatePostCommentRequest;
use App\Http\Requests\Post\UpdateRatingPostComment;
use App\Http\Resources\Post\PostCommentCollection;
use App\Http\Resources\Post\PostCommentResource;
use App\Models\Post\PostComment;
use App\Services\PostCommentService;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    /**
     * @var PostCommentService
     */
    private $postCommentService;

    public function __construct(PostCommentService $postCommentService)
    {
        $this->middleware('auth')->only(['up', 'down']);

        $this->postCommentService = $postCommentService;
    }

    /**
     * Все комментарии поста
     * @param $postId
     * @return PostCommentCollection
     */
    public function commentsByPost($postId)
    {
        $postComments = $this->postCommentService->commentsByPostId($postId);
        return new PostCommentCollection($postComments);
    }

    /**
     * Заглавные (родительские) кимментарии поста
     * @param $postId
     * @return PostCommentCollection
     */
    public function commentsParentByPost($postId)
    {
        $postComments = $this->postCommentService->commentsParentByPost($postId);
        return new PostCommentCollection($postComments);
    }

    /**
     * Комментарии одной ветки
     * @param $branchId
     * @return PostCommentCollection
     */
    public function commentsByBranch($branchId)
    {
        $postComments = $this->postCommentService->commentsTreeByBranch($branchId);
        return new PostCommentCollection($postComments);
    }

    /**
     * Добавляем комментарий
     * @param CreatePostCommentRequest $request
     * @return PostCommentCollection
     */
    public function store(CreatePostCommentRequest $request)
    {
        $comment = $this->postCommentService->create($request->validated());
        $postComments = $this->postCommentService->commentsTreeByBranch($comment->branch_id);
        return new PostCommentCollection($postComments);
    }

    /**
     * Лайк (поднимает коммент в рейтинге)
     * @param UpdateRatingPostComment $request
     * @return PostCommentResource
     */
    public function up(UpdateRatingPostComment $request){

        $comment = $this->postCommentService->up($request->id);
        return new PostCommentResource($comment);
    }

    /**
     * Дислайк (опускает коммент в рейтинге)
     * @param UpdateRatingPostComment $request
     * @return PostCommentResource
     */
    public function down(UpdateRatingPostComment $request){

        $comment = $this->postCommentService->down($request->id);
        return new PostCommentResource($comment);
    }
}
