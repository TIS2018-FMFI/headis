<?php

namespace App\Http\Controllers;

use App\Match;
use App\Rules\AllSetsValidator;
use App\Rules\SetValidator;
use App\Set;
use App\User;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPSTORM_META\type;

class SetController extends Controller
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
     */

    public function store(Request $request)
    {

        DB::transaction(function () use ($request) {
            $this->validate($request, [
                'match_id' => 'required|exists:matches,id',
                'sets' => ['required', new AllSetsValidator()]
            ]);

            foreach ($request['sets'] as $set) {
                Set::create([
                    'match_id' => $request['match_id'],
                    'score_1' => $set['score_1'],
                    'score_2' => $set['score_2']
                ]);
            }
        });

        $match = Match::find($request['match_id']);
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $match = Match::find($request['match_id']);
        DB::transaction(function () use ($request, $match) {
            $this->validate($request, [
                'match_id' => 'required|exists:matches,id',
                'sets' => ['required', new AllSetsValidator()]
            ]);

            $match->confirmed = true;
            $match->save();

            Set::where('match_id', $request['match_id'])->delete();

            $challengerPoints = 0;

            foreach ($request['sets'] as $set) {
                if ($set['score_1'] > $set['score_2']) {
                    $challengerPoints += 1;
                }
                Set::create([
                    'match_id' => $request['match_id'],
                    'score_1' => $set['score_1'],
                    'score_2' => $set['score_2']
                ]);
            }

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


        return response()->json([
            'status' => 'ok',
            'confirmed' => $match->confirmed,
            'finished' => $match->finished(),
            'sets' => $match->sets
        ]);
    }

    /**
     * Validates set.
     *
     * @param  \Illuminate\Http\Request $request
     * @return boolean
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateSet(Request $request)
    {
        $this->validate($request, [
            'score_1' => 'required',
            'score_2' => 'required',
            'set' => [new SetValidator($request['score_1'], $request['score_2'])]
        ]);

        return response()->json([
            'status' => 'ok',
            'score_1' => $request['score_1'],
            'score_2' => $request['score_2']
        ]);
    }

}