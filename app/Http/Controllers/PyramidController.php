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
//        dd(Point::pyramid());
//        dd(User::pyramid());
//        dd(DB::select("SELECT * from sets WHERE match_id=32"));

        $oldStatistic = DB::select("SELECT season_id, user_id, suma FROM
            (SELECT user_id, season_id, SUM(point) as suma FROM points group by user_id, season_id ORDER by SUM(point) ASC) ahoj WHERE season_id = ".Season::current()->id." ORDER BY suma ASC LIMIT 1") ;
       //     as ahoj WHERE season_id = ".Season::current()->id." GROUP BY season_id ORDER BY season_id DESC");
//        dd($oldStatistic);

        $actualStatistics = DB::select("SELECT date, user_id FROM points WHERE season_id = ".Season::current()->id." and point = 1 GROUP BY MONTH(date)");
//        dd(Point::hydrate($res2));
        return view('pyramid.index', [
            'users' => User::pyramid(),
            'actualStatistics' => Point::hydrate($actualStatistics),
            'statistics' => Point::hydrate($oldStatistic)
        ]);
    }

}
