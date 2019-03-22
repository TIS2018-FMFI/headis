<?php

namespace App\Http\Controllers;

use App\Challenge;
use App\Date;
use App\Mail\CreateDate;
use App\Rules\UniqueDate;
use Illuminate\Http\Request;
use Illuminate\Queue\RedisQueue;
use App\Rules\CheckHours;
use App\Rules\ValidChallengeDate;
use Illuminate\Support\Facades\Mail;

class DateController extends Controller
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

    public function store(Request $request)
    {
        $this->validate($request,[
            'challenge' => 'required|exists:challenges,id',
            'date' => ['required','date', new UniqueDate($request['challenge']), new CheckHours(), new ValidChallengeDate($request['challenge'])]
        ]);

        $date = Date::create([
            'challenge_id' => $request['challenge'],
            'date' => $request['date']
        ]);

        Mail::send(new CreateDate($date->challenge));

        $dates = Date::where('challenge_id', $request['challenge'])->get();
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
     * @throws \Exception
     */
    public function destroy(Date $date)
    {
        $challenge = Challenge::find($date->challenge_id);

        $date->delete();

        $allCurrentDates = Date::where('challenge_id', $challenge->id)->get();

        return response()->json([
            'dates' => $allCurrentDates
        ]);
    }
}
