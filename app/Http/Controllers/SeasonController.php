<?php

namespace App\Http\Controllers;

use App\Rules\ValidSeason;
use App\Season;
use Illuminate\Http\Request;

class SeasonController extends Controller
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
            'start' => 'required',
            'end' => 'required',
            'range' => [new ValidSeason($request['start'], $request['end'])]
        ]);

        $newSeason = Season::create([
           'date_from' => $request['start'],
           'date_to' => $request['end']
        ]);

        $season = [];

        $season['current'] = Season::current();
        $season['available'] = Season::available();

        //$season['available'][] = $newSeason;


        return response()->json([
            'status' => 'ok',
            'season' => $season
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Season $season
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Season $season)
    {
        if ($season == Season::current()) {
            return response()->json([
                'status' => 'wrong',
                'message' => __('season.destroyCurrent')
            ]);
        }

        $season->delete();

        $seasonArray = [];

        $seasonArray['current'] = Season::current();
        $seasonArray['available'] = Season::available();


        return response()->json([
            'status' => 'ok',
            'season' => $seasonArray
        ]);
    }
}
