<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\User\AuthorResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $useRating = !empty($this->useRating) ? $this->useRating->up : null;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'first_image' => new PostImageResource($this->first_image),
            'use_rating' => $useRating,
            'author' => new AuthorResource($this->author),
            'status' => $this->status,
            'rating' => $this->rating,
            'created_at_humans' => $this->created_at_humans,
        ];
    }
}
