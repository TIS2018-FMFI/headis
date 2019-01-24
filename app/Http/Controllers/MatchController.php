<?php

namespace App\Http\Controllers;

use App\Match;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatchController extends Controller
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
     * Display the specified resource.
     *
     * @param  Match $match
     * @return \Illuminate\Http\Response
     */
    public function show(Match $match)
    {
        if (!$match->isMember()){
            return back();
        }
        if ($match->confirmed){
            return redirect('/users/'.auth()->user()->id);
        }
        return view('match.show', [
            'match' => $match->load(['sets', 'challenge.challenger', 'challenge.asked']),
            'finished' => $match->finished()
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
            'challenge_id' => 'required|exists:challenges,id',
            'date' => 'required|exists:dates,id'
        ]);

        $match = Match::create([
            'challenge_id' => $request['challenge_id'],
            'date_id' => $request['date']
        ]);

        return redirect('/matches/'.$match->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Match $match
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Match $match)
    {
        $match->confirmed = $request['data']['confirmed'];
        $match->save();
        if ($match->finished()) {
            DB::transaction(function () use ($match) {
                $sets = $match->sets;
                $challengerPoints = 0;
                foreach ($sets as $set) {
                    if ($set->score_1 > $set->score_2) {
                        $challengerPoints += 1;
                    }
                }
                $match->confirmed = true;
                $match->save();
                if ($challengerPoints == 2) {
                    $challenger = User::find($match->challenge->challenger->id);
                    $asked = User::find($match->challenge->asked->id);
                    $pos1 = $asked->position;
                    $asked->position = $challenger->position;
                    $challenger->position = $pos1;
                    $asked->save();
                    $challenger->save();
                }

            });
        }

        if (!$match->confirmed){
            //Posli e-mail redaktorovi
        }
        return response()->json([
           'status' => $match->confirmed ? 'success' : 'danger',
            'message' => ''
        ]);
    }

}
