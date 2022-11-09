<?php
namespace App\Services;


use App\Http\Resources\Post\PostCommentBranchCollection;
use App\Http\Resources\Post\PostCommentCollection;
use App\Models\Post\PostComment;
use App\Models\Post\PostCommentUp;
use Illuminate\Support\Facades\Auth;

class PostCommentService
{

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        $userId = Auth::check() ? Auth::id() : 0;

        $comment = PostComment::select([
            'post_comments.id',
            'post_comments.author_id',
            'post_comments.post_id',
            'post_comments.parent_id',
            'post_comments.branch_id',
            'post_comments.comment',
            'post_comments.status_id',
            'post_comments.created_at',
            'post_comments.updated_at',
            'post_comment_ups.up'
        ])
            ->leftJoin('post_comment_ups', function($join) use ($userId) {
                $join->on('post_comment_ups.comment_id', '=', 'post_comments.id')
                    ->where('post_comment_ups.user_id', '=', $userId);
            })
            ->with(['author:id,name,avatar', 'status:id,title'])
            ->withCount(['up', 'down'])
            ->find($id);

        return $comment;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function commentsByPostId($id)
    {
        $postComments = PostComment::query()
            ->commentsPost()
            ->where('post_id', $id)
            ->orderByDesc('created_at')
            ->get();

        return $postComments;
    }

    /**
     * Получаем ветку комментариев. Все комметарии одного родительского комментария
     * @param $branchId
     * @return mixed
     */
    public function commentsByBranch($branchId)
    {
        $postComments = PostComment::query()
            ->commentsPost()
            ->where('branch_id', $branchId)
            ->orWhere('id', $branchId)
            ->orderByDesc('created_at')
            ->get();

        return $postComments;
    }

    /**
     * Получаем ветку комментариев в виде дерева
     * @param $branchId
     * @return \Illuminate\Support\Collection
     */
    public function commentsTreeByBranch($branchId)
    {
        $postComments = $this->commentsByBranch($branchId);
        $postComments = new PostCommentBranchCollection($postComments);
        $postComments = $this->commentsTree($postComments);
        //dd($postComments);
        return $postComments;
    }

    /**
     * Получаем комментарии для дерева коментариев
     * @param PostComment $comment
     * @return PostCommentBranchCollection|PostCommentCollection
     */
    public function commentsTreeByComment(PostComment $comment)
    {
        if($comment->branch_id){
            $postComments = $this->commentsTreeByBranch($comment->branch_id);
            $postComments = new PostCommentBranchCollection($postComments);
        }else{
            $postComments = $this->commentsByPostId($comment->post_id);
            $postComments = new PostCommentCollection($postComments);
        }
        return $postComments;
    }

    /**
     * Формируем дерево комментариев
     * @param PostCommentBranchCollection $postComments
     * @param null|int $parentId
     * @return \Illuminate\Support\Collection
     */
    public function commentsTree(&$postComments, $parentId = null){

        $commentsTree = collect();

        foreach ($postComments as $comment) {
            if($comment->parent_id === $parentId){
                $children = $this->commentsTree($postComments, $comment->id);
                if($children){
                    $comment->setAttribute('children', $children);
                }
                $commentsTree->push($comment);
                unset($postComments[$comment->id]);
            }
        }
        return $commentsTree;
    }

    /**
     * Заглавные (родительские) кимментарии поста
     * @param $postId
     * @return mixed
     */
    public function commentsParentByPost($postId)
    {
        $postComments = PostComment::query()
            ->commentsPost()
            ->withCount(['children'])
            //->with(['children'])
            ->where('post_id', $postId)
            ->whereNull('parent_id')
            ->orderByDesc('created_at')
            ->orderByDesc('created_at')
            ->get();

        return $postComments;
    }

    /**
     * Добавляем комментарий
     * @param $newCommentData
     * @return mixed
     */
    public function create($newCommentData)
    {
        $comment = PostComment::create([
            'author_id' => $newCommentData['author_id'],
            'post_id' => $newCommentData['post_id'],
            'branch_id' => !empty($newCommentData['branch_id']) ? $newCommentData['branch_id'] : null,
            'parent_id' => !empty($newCommentData['parent_id']) ? $newCommentData['parent_id'] : null,
            'comment' => $newCommentData['comment'],
            'status_id' => $newCommentData['status_id'],
        ]);

        return $comment;
    }

    /**
     * Лайк комментарию (поднимает пост в рейтинге)
     * @param $id
     * @return mixed
     */
    public function up($id)
    {
        $comment = PostComment::findOrFail($id);
        $userUseRating = PostCommentUp::where('user_id', Auth::id())->where('comment_id', $comment->id)->first();

        if($userUseRating){
            $userUseRating->delete();
        }else{
            $comment->up()->create([
                'user_id' => Auth::id(),
                'up' => 1,
            ]);
        }

        $postUpdate = $this->getById($id);

        return $postUpdate;
    }

    /**
     * Дизлайк комментарию (опускаем пост в рейтинге)
     * @param $id
     * @return mixed
     */
    public function down($id)
    {
        $post = PostComment::findOrFail($id);
        $userUseRating = PostCommentUp::where('user_id', Auth::id())->where('comment_id', $post->id)->first();

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
