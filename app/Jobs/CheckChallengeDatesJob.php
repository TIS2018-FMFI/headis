<?php

namespace App\Jobs;

use App\Challenge;
use App\Date;
use App\Match;
use App\Season;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class CheckChallengeDatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $challenge;

    /**
     * Create a new job instance.
     *
     * @param $challenge_id
     */
    public function __construct($challenge_id)
    {
        $this->challenge = Challenge::find($challenge_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->challenge->match != null){
            return;
        }
        DB::transaction(function () {

            $dates = $this->challenge->dates;

            if (count($dates) < 5) {
                $date = Date::create([
                    'challenge_id' => $this->challenge->id,
                    'date' => Carbon::now()
                ]);
                Match::create([
                    'challenge_id' => $this->challenge->id,
                    'date_id' => $date->id,
                    'confirmed' => true,
                    'type' => 'notPenalized',
                    'season_id' => Season::current()->id
                ]);
                $matchesWhereNotPenalized = $this->challenge->asked->matchWithNotPenalized;
                if (sizeof($matchesWhereNotPenalized) == 3){
                    foreach ($matchesWhereNotPenalized as $match){
                        $match->type = 'penalized';
                        $match->save();
                    }
                    $user = User::find($this->challenge->asked->id);
                    $position = $user->position;
                    $newPosition = pow(floor(sqrt($user->position - 1)) + 1, 2) + 1;
                    $usersToMove = User::where('position', '>', $position)->where('position', '<=', $newPosition)->get();
                    $user->position = $newPosition;
                    $user->save();
                    foreach ($usersToMove as $userToMove){
                        $userToMove->position--;
                        $userToMove->save();
                    }
                }
            }


        });
    }
}
