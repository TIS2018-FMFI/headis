<?php

namespace App\Http\Controllers;

use App\Challenge;
use App\Rules\CanChallenge;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\DocBlock\Tags\Author;

class ChallengeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
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
            'user' => ['required', 'exists:users,id', new CanChallenge(auth()->user()->id, $request['user'])]
        ]);
//        dd($request['user']);
        $challenge = Challenge::create([
            'user_id_1' => auth()->user()->id,
            'user_id_2' => intval($request['user']),
            'created_date' => Carbon::now()->toDateString()
        ]);
        return redirect('/challenges/'.$challenge->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function show(Challenge $challenge)
    {
        if (!$challenge->isMember()){
            return back();
        }
        if ($challenge->match){
            return redirect('/matches/'.$challenge->match->id);
        }
        return view('challenge.show', [
            'challenge' => $challenge->load(['challenger', 'asked', 'dates', 'comments']),
        ]);
    }
}
