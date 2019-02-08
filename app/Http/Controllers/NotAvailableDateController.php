<?php

namespace App\Http\Controllers;

use App\Date;
use App\NotAvailableDate;
use App\Season;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotAvailableDateController extends Controller
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
        $currentSeason = Season::current();
        if ($currentSeason == null) {
            return response()->json([

            ]);
        }

        $this->validate($request, [
           'date' => 'required|date|unique:dates,date|after_or_equal:today'
        ]);


        NotAvailableDate::create([
            'season_id' => $currentSeason->id,
            'date' => $request['date']
        ]);

        $notAvailableDates = [];

        $start = Carbon::parse($currentSeason->date_from);

        $end = Carbon::parse($currentSeason->date_to);

        $notAvailableDates['dates'] = NotAvailableDate::getNotAvailableDatesInRange($start, $end);

        $dates = Date::getDatesInRange($start, $end);

        foreach ($dates as $date) {
            $notAvailableDates['picker'][] = Carbon::parse($date->date)->toDateString();
        }

        return response()->json([
            'status' => 'ok',
            'notAvailableDates' => $notAvailableDates
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  NotAvailableDate $notAvailableDate
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(NotAvailableDate $notAvailableDate)
    {

    }
}
