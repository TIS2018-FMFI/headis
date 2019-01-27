<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ManagerController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'redactor']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $translations = [];

        $translations['posts.add'] = __('posts.add');
        $translations['posts.title'] = __('posts.title');
        $translations['posts.intro_text'] = __('posts.intro_text');
        $translations['posts.image'] = __('posts.image');
        $translations['posts.text'] = __('posts.text');
        $translations['posts.addBtn'] = __('posts.addBtn');
        $translations['users.deactivate'] = __('users.deactivate');
        $translations['users.deactivateBtn'] = __('users.deactivateBtn');
        $translations['users.reactivate'] = __('users.reactivate');
        $translations['users.reactivateBtn'] = __('users.reactivateBtn');
        $translations['users.noFoundUsers'] = __('users.noFoundUsers');

        return view('manager.index', [
            'translations' => $translations,
            'canDeactivateUsers' => User::canDeactivate(),
            'canReactivateUsers' => User::canActivate()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

}
