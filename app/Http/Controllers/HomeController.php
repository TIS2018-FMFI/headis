<?php

namespace App\Http\Controllers;

use App\Challenge;
use App\Match;
use App\Post;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::homeLatest();

        return view('home.index', [
            'posts' => $posts,
            'currentChallenges' => Challenge::current(),
            'currentMatches' => Match::current()
        ]);
    }

    public function privacyPolicy()
    {
        return view('home.privacy');
    }
}
