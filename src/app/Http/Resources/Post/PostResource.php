<?php

namespace App\Http\Resources\Post;

use App\Models\Post\Post;
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
        $useRating = !empty($this->useUating) ? $this->useUating->up : null;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'preview_img' => $this->preview_img,
            'preview' => $this->preview,
            'up_count' => $this->up_count,
            'down_count' => $this->down_count,
            'author' => $this->author,
            'status' => $this->status,
            'rating' => $this->rating,
            'use_rating' => $useRating,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_at_humans' => $this->created_at_humans,
        ];
    }
}
