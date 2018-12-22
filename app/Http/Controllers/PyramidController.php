<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class PyramidController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        dd(Point::pyramid());
//        dd(User::pyramid());
        return view('pyramid.index', [
            'users' => User::pyramid()
        ]);
    }

}
