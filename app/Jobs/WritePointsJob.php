<?php

namespace App\Jobs;

use App\Point;
use App\Season;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class WritePointsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $today;

    /**
     * Create a new job instance.
     *
     * @param $today
     */
    public function __construct($today = false)
    {
        $this->today = $today;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (Season::current() == null){
            return;
        }
        DB::transaction(function () {
            $users = User::where('isRedactor', false)->get();
            $season = Season::current();
            foreach ($users as $user){
                Point::create([
                    'user_id' => $user->id,
                    'date' => $this->today ? Carbon::today() : Carbon::today()->subDays(2),
                    'point' => $user->position,
                    'season_id' => $season->id
                ]);
            }
        });

    }
}
