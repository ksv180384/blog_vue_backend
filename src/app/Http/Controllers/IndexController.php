<?php

namespace App\Http\Controllers;

use App\Services\PostService;

class IndexController extends Controller
{
    public function index()
    {


        return view('welcome');
    }
}
