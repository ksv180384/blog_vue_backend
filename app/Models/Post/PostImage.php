<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PostImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'path',
    ];

    public $timestamps = false;

    protected $appends = ['path_storage'];

    public function getPathStorageAttribute()
    {
        return env('APP_URL') . Storage::url('posts/' . $this->path);
    }

}
