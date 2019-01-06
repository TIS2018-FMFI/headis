<?php

namespace App\Http\Controllers;

use App\Date;
use Illuminate\Http\Request;

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
     * Remove the specified resource from storage.
     *
     * @param  Date $date
     * @return \Illuminate\Http\Response
     */
    public function destroy(Date $date)
    {
        //
    }
}
