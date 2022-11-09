<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
    public function index()
    {
        //phpinfo();
        return view('welcome');
    }
}
