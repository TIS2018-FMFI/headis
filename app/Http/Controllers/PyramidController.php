<?php

namespace App\Http\Controllers;

use App\Point;
use App\Season;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PyramidController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $season = Season::current();
        if ($season == null){
            $season = Season::orderBy('date_to', 'desc')->first();
        }
        $oldStatistic = DB::select("SELECT season_id, user_id, suma FROM
            (SELECT user_id, season_id, SUM(point) as suma FROM points group by user_id, season_id ORDER by SUM(point) ASC) ahoj 
            WHERE season_id = ".($season->id-1)." ORDER BY suma ASC LIMIT 1") ;

        $actualStatistics = DB::select("SELECT date, user_id FROM points 
            WHERE season_id = ".$season->id." and point = 1 GROUP BY MONTH(date)");
        return view('pyramid.index', [
            'users' => User::pyramid(),
            'actualStatistics' => Point::hydrate($actualStatistics),
            'statistics' => Point::hydrate($oldStatistic)
        ]);
    }

}
