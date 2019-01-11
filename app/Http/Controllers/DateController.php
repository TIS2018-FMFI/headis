<?php

namespace App\Http\Controllers;

use App\Challenge;
use App\Date;
use Illuminate\Http\Request;
use Illuminate\Queue\RedisQueue;

class DateController extends Controller
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

    public function store(Request $request)
    {

        $this->validate($request,[
            'data.challenge' => 'required',
            'data.date' => 'required|date|after_or_equal:today'
        ]);

        Date::create([
            'challenge_id' => $request['data']['challenge'],
            'date' => $request['data']['date']
        ]);

        $dates = Date::where('challenge_id', $request['data']['challenge'])->get();
        return response()->json([
            'dates' => $dates,
            'status' => 'ok'
        ]);
    }

    /**
     * Update the specified resource from storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $challenge = null;

        foreach ($request["data"]["dates"] as $id) {
            $date = Date::find($id);
            $date->rejected = true;
            $date->save();
            if (!$challenge) {
                $challenge = Challenge::find($date->challenge_id);
            }
        }

        $allCurrentDates = Date::where('challenge_id', $challenge->id)->where('rejected', false)->get();

        return response()->json([
           'dates' => $allCurrentDates
        ]);
    }
}
