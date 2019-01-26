<?php

namespace App\Http\Controllers;

use App\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'redactor'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::allAvailable();

        return view('post.index', [
            'posts' => $posts
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('post.show', [
            'post' => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Post $post
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Post $post)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'text' => 'required|string',
            'intro_text' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png'
        ]);

        $fileName = null;
        if (isset($request['image']) && $request['image'] != null) {
            $file = $request['image'];
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move(public_path('/images'), $fileName);
            if (file_exists('/images/'.$post->image)){
                unlink('/images/'.$post->image);
            }
        }

        DB::transaction(function () use ($post, $request, $fileName){
            $post->title = $request->title;
            if ($fileName) {
                $post->image = $fileName;
            }
            $post->text = htmlentities($request['text']);
            $post->intro_text = $request['intro_text'];
            $post->save();
        });

        return response()->json([
            'status' => 'ok',
            'url' => '/posts/'.$post->id
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'text' => 'required|string',
            'intro_text' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png'
        ]);

        $fileName = null;
        if (isset($request['image'])) {
            $file = $request['image'];
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move(public_path('/images'), $fileName);
        }

        Post::create([
            'title' => $request['title'],
            'intro_text' => isset($request['intro_text']) ? $request['intro_text'] : '',
            'text' => htmlentities($request['text']),
            'image' => $fileName
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'good'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $translations = [];
        $translations['posts.title'] = __('posts.title');
        $translations['posts.text'] = __('posts.text');
        $translations['posts.intro_text'] = __('posts.intro_text');
        $translations['posts.image'] = __('posts.image');
        $translations['posts.edit'] = __('posts.edit');
        $translations['posts.editBtn'] = __('posts.editBtn');

        return view('post.edit', [
            'post' => $post,
            'translations' => $translations,
            'rte' => html_entity_decode($post->text)
        ]);
    }

}
