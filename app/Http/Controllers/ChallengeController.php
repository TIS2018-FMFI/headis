<?php

namespace App\Http\Controllers;

use App\Challenge;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        return view('challenge.show', [
            'challenge' => $challenge->load(['challenger', 'asked', 'dates', 'comments']),
        ]);
    }
}
