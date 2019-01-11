<?php

namespace App\Http\Controllers;

use App\Match;
use App\Set;
use Illuminate\Http\Request;

class SetController extends Controller
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
        Set::create([
            'match_id' => $request['data']['match_id'],
            'score_1' => $request['data']['score1'],
            'score_2' => $request['data']['score2']
        ]);
        $match = Match::find($request['data']['match_id']);
        $sets = $match->sets;

        return response()->json([
            'sets' => $sets,
            'finished' => $match->finished()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Set $set
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Set $set)
    {
        //
    }

}
