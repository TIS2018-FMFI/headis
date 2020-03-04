<?php

namespace App\Http\Controllers;

use App\Date;
use App\Match;
use App\NotAvailableDate;
use App\Season;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManagerController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'redactor']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $translations = [];

        $translations['posts.add'] = __('posts.add');
        $translations['posts.title'] = __('posts.title');
        $translations['posts.intro_text'] = __('posts.intro_text');
        $translations['posts.image'] = __('posts.image');
        $translations['posts.text'] = __('posts.text');
        $translations['posts.addBtn'] = __('posts.addBtn');
        $translations['posts.hidden'] = __('posts.hidden');
        $translations['users.deactivate'] = __('users.deactivate');
        $translations['users.deactivateBtn'] = __('users.deactivateBtn');
        $translations['users.reactivate'] = __('users.reactivate');
        $translations['users.reactivateBtn'] = __('users.reactivateBtn');
        $translations['users.noFoundUsers'] = __('users.noFoundUsers');
        $translations['season.add'] = __('season.add');
        $translations['season.choose_start'] = __('season.choose_start');
        $translations['season.choose_end'] = __('season.choose_end');
        $translations['season.can_not_change'] = __('season.can_not_change');
        $translations['season.delete'] = __('season.delete');
        $translations['season.deleteBtn'] = __('season.deleteBtn');
        $translations['season.can_not_delete'] = __('season.can_not_delete');
        $translations['not_available_dates.add'] = __('not_available_dates.add');
        $translations['not_available_dates.addBtn'] = __('not_available_dates.addBtn');
        $translations['not_available_dates.delete'] = __('not_available_dates.delete');
        $translations['not_available_dates.deleteBtn'] = __('not_available_dates.deleteBtn');
        $translations['not_available_dates.choose_date'] = __('not_available_dates.choose_date');
        $translations['not_available_dates.noFoundDates'] = __('not_available_dates.noFoundDates');
        $translations['not_available_dates.addNotInCurrentSeason'] = __('not_available_dates.addNotInCurrentSeason');
        $translations['not_available_dates.destroyNotInCurrentSeason'] = __('not_available_dates.destroyNotInCurrentSeason');

        $season = [];

        $currentSeason = Season::current();
        $season['current'] = $currentSeason;
        $season['available'] = Season::available();

        $notAvailableDates = [];


        if ($currentSeason) {
            $start = Carbon::parse($currentSeason->date_from);
            $end = Carbon::parse($currentSeason->date_to);

            $notAvailableDates['dates'] = NotAvailableDate::getNotAvailableDatesInRange($start, $end);

            $dates = Date::getDatesInRange($start, $end);

            foreach ($dates as $date) {
                $notAvailableDates['picker'][] = Carbon::parse($date->date)->toDateString();
            }
            foreach ($notAvailableDates['dates'] as $date) {
                $notAvailableDates['picker'][] = $date->date;
            }
        }


        return view('manager.index', [
            'translations' => $translations,
            'canDeactivateUsers' => User::canDeactivate(['id', 'user_name']),
            'canReactivateUsers' => User::canActivate(['id', 'user_name']),
            'season' => $season,
            'notAvailableDates' => $notAvailableDates,
            'currentMatches' => Match::allCurrentMatches(),
            'users' => User::where('isRedactor',0)->orderBy('position')->get(['id', 'user_name', 'position'])
        ]);
    }
}
