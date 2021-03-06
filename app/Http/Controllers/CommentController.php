<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Mail\CreateComment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CommentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'activeSeason']);
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
        $this->validate($request,[
            'data.text' => 'required'
        ]);

        $comment = Comment::create([
            'challenge_id' => $request['data']['challenge'],
            'date' => Carbon::now(),
            'user_id' => $request['data']['user_id'],
            'text' => $request['data']['text']

        ]);

        Mail::send(new CreateComment($comment));

        $comments = Comment::where('challenge_id', $request['data']['challenge'])->get();
        return response()->json([
            'comments' => $comments,
            'status' => 'ok'
        ]);
    }

}
