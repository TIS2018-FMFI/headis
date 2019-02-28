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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
            $users = User::all();
            $season = Season::current();
            foreach ($users as $user){
                Point::create([
                    'user_id' => $user->id,
                    'date' => Carbon::now()->subDays(2),
                    'point' => $user->position,
                    'season_id' => $season->id
                ]);
            }
        });

    }
}
