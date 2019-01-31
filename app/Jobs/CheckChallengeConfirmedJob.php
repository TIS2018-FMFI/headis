<?php

namespace App\Jobs;

use App\Challenge;
use App\Date;
use App\Match;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class CheckChallengeConfirmedJob implements ShouldQueue
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
        DB::transaction(function () {
            $match = $this->challenge->match;

            if ($match == null) {
                $date = Date::create([
                    'challenge_id' => $this->challenge->id,
                    'date' => Carbon::now()
                ]);
                Match::create([
                    'challenge_id' => $this->challenge->id,
                    'date_id' => $date->id,
                    'confirmed' => true,
                    'type' => 'contractedLoss'
                ]);
            }

        });
    }
}
