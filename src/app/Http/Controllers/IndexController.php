<?php

namespace App\Http\Controllers;

use App\Services\PostService;

class IndexController extends Controller
{
    public function index()
    {
        phpinfo();

        return view('welcome');
    }
}
