<?php

namespace App\Http\Controllers;

use App\Point;
use App\Season;
use App\User;
use Carbon\Carbon;
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


        if ($season == null) {
            $season = Season::current();
            $maxLevel = floor(sqrt(User::max('position')-1));
            if ($season == null){
                $season = Season::orderBy('date_to', 'desc')->first();
            }
            $pyramid = User::pyramid();
        } else {
            $pyramid = $season->pyramid;
            $maxLevel = floor(sqrt(sizeof($pyramid)));
        }
        //dd($pyramid);

        $statistic = [];
        foreach (Season::where('date_to','<',Carbon::today()->toDateString())->get() as $s) {
            $statistic[] = DB::select("SELECT season_id, user_id, suma FROM
                    (SELECT user_id, season_id, SUM(point) as suma FROM points group by user_id, season_id ORDER by SUM(point) ASC) ahoj 
                    WHERE season_id = ".($s->id)." ORDER BY suma ASC LIMIT 1") ;
        }

        $statisticPoints = [];
        foreach ($statistic as $items) {
            if ($items) {
                $statisticPoints[] = Point::hydrate($items)[0];
            }
        }
        //dd($statisticPoints);

        //dd($statistic);

//        $oldStatistic = DB::select("SELECT season_id, user_id, suma FROM
//            (SELECT user_id, season_id, SUM(point) as suma FROM points group by user_id, season_id HAVING SUM(point) > 0 ORDER by SUM(point) ASC) ahoj
//            WHERE season_id = ".($season->id-1)." ORDER BY suma ASC LIMIT 1") ;

        $actualStatistics = DB::select("SELECT date, user_id FROM points 
            WHERE season_id = ".$season->id." and point = 1 GROUP BY MONTH(date)");
        return view('pyramid.index', [
            'season' => $season,
            'seasons' => Season::orderBy('date_to', 'desc')->get(),
            'maxLevel' => $maxLevel,
            'users' => $pyramid,
            'actualStatistics' => Point::hydrate($actualStatistics),
            'statistics' => $statisticPoints
        ]);
    }

}
