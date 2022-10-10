<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\User\AuthorResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{

    /**
     * Название объекта который будет в json
     * @var string
     */
    public static $wrap = 'post';

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
            'images' => $this->images ? (new PostImageCollection($this->images))->slice(1)->all() : null,
            'first_image' => new PostImageResource($this->first_image),
            'up_count' => $this->up_count,
            'down_count' => $this->down_count,
            'author' => new AuthorResource($this->author),
            'status' => $this->status,
            'rating' => $this->rating,
            'use_rating' => $useRating,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_at_humans' => $this->created_at_humans,
        ];
    }
}
