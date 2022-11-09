<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCommentStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'sort',
    ];

    public $timestamps = false;
}
