<?php

namespace App\Http\Controllers;

use App\Date;
use App\Jobs\CheckMatchConfirmedJob;
use App\Jobs\CheckSetsJob;
use App\Mail\MatchRejectedAsked;
use App\Mail\RejectedMatch;
use App\Mail\MatchDateConfirmed;
use App\Match;
use App\NotAvailableDate;
use App\Rules\ValidChallengeDate;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MatchController extends Controller
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
        $translations = array();
        $translations['matches.challenger'] = __('matches.challenger');
        $translations['matches.challenged'] = __('matches.challenged');
        $translations['matches.match'] = __('matches.match');
        $translations['matches.remove_set'] = __('matches.remove_set');
        $translations['matches.add_set'] = __('matches.add_set');
        $translations['matches.add_title'] = __('matches.add_title');
        $translations['matches.add'] = __('matches.add');
        $translations['matches.confirm_match_title'] = __('matches.confirm_match_title');
        $translations['matches.confirm'] = __('matches.confirm');
        $translations['matches.reject_match'] = __('matches.reject_match');
        $translations['matches.reset'] = __('matches.reset');
        $translations['matches.cannot_edit_match'] = __('matches.cannot_edit_match');
        $translations['matches.not_available_sets'] = __('matches.not_available_sets');
        $translations['matches.cannot_add_sets'] = __('matches.cannot_add_sets');
        $translations['matches.are_not_available_sets'] = __('matches.are_not_available_sets');
        return view('match.show', [
            'match' => $match->load(['sets', 'challenge.challenger', 'challenge.asked', 'date']),
            'finished' => $match->finished(),
            'translations' => $translations,
            'canAddSets' => Carbon::now(config('app.timezone'))->gte(Carbon::parse($match->date->date))
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
            'date' => ['required','exists:dates,date', new ValidChallengeDate($request['challenge_id'])]
        ]);

        $date = Date::where('date', $request['date'])->where('challenge_id', $request['challenge_id'])->first();

        $match = Match::create([
            'challenge_id' => $request['challenge_id'],
            'date_id' => $date->id
        ]);

        $date = NotAvailableDate::addDaysTo(Carbon::now(), 4);
        dispatch(new CheckMatchConfirmedJob($match->id))->delay($date);

        $date = NotAvailableDate::addDaysTo(Carbon::now(), 3);
        dispatch(new CheckSetsJob($match->id))->delay($date);

        Mail::send(new MatchDateConfirmed($match));

        return response()->json([
            'status' => 'success',
            'url' => '/matches/'.$match->id
        ]);
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
        if ($match->finished() && $match->confirmed) {
            DB::transaction(function () use ($match) {
                $sets = $match->sets;
                $challengerPoints = 0;
                foreach ($sets as $set) {
                    if ($set->score_1 > $set->score_2) {
                        $challengerPoints += 1;
                    }
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
        }

        if (!$match->confirmed){
            Mail::send(new RejectedMatch($match));
            Mail::send(new MatchRejectedAsked($match));
        }
        return response()->json([
           'status' => $match->confirmed ? 'success' : 'danger',
            'message' => ''
        ]);
    }

}
