<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCommentUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'up',
    ];

    public $timestamps = false;
}
