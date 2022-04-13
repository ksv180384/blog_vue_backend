<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Resources\Json\JsonResource;

class PostCommentResource extends JsonResource
{
    /**
     * Название объекта который будет в json
     * @var string
     */
    public static $wrap = 'comment';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable|array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'post_id' => $this->post_id,
            'parent_id' => $this->parent_id,
            'branch_id' => $this->branch_id,
            'comment' => $this->comment,
            'up_count' => $this->up_count,
            'down_count' => $this->down_count,
            'author' => $this->author,
            'status' => $this->status,
            'rating' => $this->rating,
            'use_rating' => $this->up,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_at_humans' => $this->created_at_humans,
            'children' => !empty($this->children) ? new PostCommentCollection($this->children) : null,
        ];
    }
}
