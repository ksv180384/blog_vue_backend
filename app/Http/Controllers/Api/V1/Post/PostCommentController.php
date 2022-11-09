<?php

namespace App\Http\Controllers\Api\V1\Post;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Post\CreatePostCommentRequest;
use App\Http\Requests\Post\UpdateRatingPostComment;
use App\Http\Resources\Post\PostCommentBranchCollection;
use App\Http\Resources\Post\PostCommentCollection;
use App\Http\Resources\Post\PostCommentResource;
use App\Services\PostCommentService;

class PostCommentController extends BaseController
{
    /**
     * @var PostCommentService
     */
    private $postCommentService;

    public function __construct(PostCommentService $postCommentService)
    {
        parent::__construct();

        $this->middleware('auth')->only(['up', 'down', 'store']);

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
     * @return PostCommentBranchCollection
     */
    public function commentsByBranch($branchId)
    {
        $postComments = $this->postCommentService->commentsTreeByBranch($branchId);
        return new PostCommentBranchCollection($postComments);
    }

    /**
     * Добавляем комментарий
     * @param CreatePostCommentRequest $request
     * @return PostCommentBranchCollection|PostCommentCollection
     */
    public function store(CreatePostCommentRequest $request)
    {
        $comment = $this->postCommentService->create($request->validated());
        $postComments = $this->postCommentService->commentsTreeByComment($comment);
        return response()->json(['comments' => $postComments]);
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
