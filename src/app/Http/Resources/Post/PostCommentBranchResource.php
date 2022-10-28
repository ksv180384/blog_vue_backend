<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\User\AuthorResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostCommentBranchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'post_id' => $this->post_id,
            'parent_id' => $this->parent_id,
            'branch_id' => $this->branch_id,
            'comment' => $this->comment,
            //'up_count' => $this->up_count,
            //'down_count' => $this->down_count,
            'author' => new AuthorResource($this->author),
            'status' => $this->status,
            'rating' => $this->rating,
            'use_rating' => $this->useRating ? $this->useRating->up : 0,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_at_humans' => $this->created_at_humans,
            'children' => !empty($this->children) ? new PostCommentBranchCollection($this->children) : null,
            //'children' => !empty($this->children) ? new PostCommentChildrenCollection($this->children) : null,
            'children_count' => $this->children_count,
        ];
    }
}
