<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostStatus extends Model
{
    use HasFactory;

    protected $table = 'post_statuses';

    protected $fillable = [
        'title',
        'slug',
        'sort',
    ];

    public $timestamps = false;
}
