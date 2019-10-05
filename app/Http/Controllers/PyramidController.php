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
     * Display a listing of the resource.
     *
     * @param Season|null $season
     * @return \Illuminate\Http\Response
     */
    public function index(Season $season = null)
    {
        $maxLevel = floor(sqrt(User::max('position')-1));

        if ($season == null) {
            $season = Season::current();
            if ($season == null){
                $season = Season::orderBy('date_to', 'desc')->first();
            }
            $pyramid = User::pyramid();
        } else {
            $pyramid = $season->pyramid;
        }
        //dd($pyramid);

        $oldStatistic = DB::select("SELECT season_id, user_id, suma FROM
            (SELECT user_id, season_id, SUM(point) as suma FROM points group by user_id, season_id ORDER by SUM(point) ASC) ahoj 
            WHERE season_id = ".($season->id-1)." ORDER BY suma ASC LIMIT 1") ;

        $actualStatistics = DB::select("SELECT date, user_id FROM points 
            WHERE season_id = ".$season->id." and point = 1 GROUP BY MONTH(date)");
        return view('pyramid.index', [
            'season' => $season,
            'seasons' => Season::all(),
            'maxLevel' => $maxLevel,
            'users' => $pyramid,
            'actualStatistics' => Point::hydrate($actualStatistics),
            'statistics' => Point::hydrate($oldStatistic)
        ]);
    }

}
