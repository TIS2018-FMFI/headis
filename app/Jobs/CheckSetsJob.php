<?php

namespace App\Jobs;

use App\Match;
use App\Set;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class CheckSetsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $match;

    /**
     * Create a new job instance.
     *
     * @param $match_id
     */
    public function __construct($match_id)
    {
        $this->match = Match::find($match_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::transaction(function () {
            $sets = $this->match->sets;

            if (count($sets) == 0){
                foreach (range(0, 1) as $number) {
                    Set::create([
                        'match_id' => $this->match->id,
                        'score_1' => 11,
                        'score_2' => 0
                    ]);
                }
                $this->match->confirmed = true;
                $this->match->save();

                $challenger = User::find($this->match->challenge->challenger->id);
                $asked = User::find($this->match->challenge->asked->id);
                $pos1 = $asked->position;
                $asked->position = $challenger->position;
                $challenger->position = $pos1;
                $asked->save();
                $challenger->save();
            }
        });
    }
}
