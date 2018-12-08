<?php

namespace App\Http\Controllers;

use App\Post;

class HomeController extends Controller
{

    public function index()
    {
        $posts = Post::homeLatest();

        return view('home.index', [
            'posts' => $posts
        ]);
    }
}
