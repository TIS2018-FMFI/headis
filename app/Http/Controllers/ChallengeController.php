<?php

namespace App\Http\Controllers;

use App\Challenge;
use App\Mail\CreatedChallenge;
use App\Rules\CanChallenge;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\DocBlock\Tags\Author;
use App\NotAvailableDate;

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
        Mail::send(new CreatedChallenge($challenge));
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

        $translations = array();
        $translations['challenges.challenge'] = __('challenges.challenge');
        $translations['challenges.challenger'] = __('challenges.challenger');
        $translations['challenges.challenged'] = __('challenges.challenged');
        $translations['challenges.dates'] = __('challenges.dates');
        $translations['challenges.messages'] = __('challenges.messages');
        $translations['challenges.type_a_message'] = __('challenges.type_a_message');
        $translations['challenges.no_dates_were_added'] = __('challenges.no_dates_were_added');
        $translations['challenges.add_a_date'] = __('challenges.add_a_date');
        $translations['challenges.choose_a_date'] = __('challenges.choose_a_date');
        $translations['challenges.add'] = __('challenges.add');
        $translations['challenges.confirm'] = __('challenges.confirm');
        $translations['challenges.delete'] = __('challenges.delete');

        $dates = [];
        $start = Carbon::today();
        $created_date = Carbon::parse($challenge->create_date);
        $end = NotAvailableDate::addDaysTo($created_date, 10);
        $dates['start'] = $start->toDateString();
        $dates['end'] = $end->toDateString();
        $notAvailableDates = NotAvailableDate::getNotAvailableDatesInRange($start, $end);
        $dates['notAvailable'] = [];
        foreach ($notAvailableDates as $date) {
            $dates['notAvailable'][] = $date->date;
        }

        return view('challenge.show', [
            'challenge' => $challenge->load(['challenger', 'asked', 'dates', 'comments']),
            'translations' => $translations,
            'dates' => $dates
        ]);
    }
}
