<?php

namespace App\Http\Controllers;

use App\Models\Post\Post;
use App\Services\PostService;

class IndexController extends Controller
{
    public function index()
    {
        $post = Post::find(1);

        $imgUrl = $post->getFirstMediaURL('posts', 'small');

        //dd($post->getMedia('posts')->first()->getUrl());

        return '<img src="' . $imgUrl . '">';

        return view('welcome');
    }
}
