<?php

namespace App\Jobs;

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

    public $match_id;

    /**
     * Create a new job instance.
     *
     * @param $match_id
     */
    public function __construct($match_id)
    {
        $this->match_id = $match_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::transaction(function () {
            $match = Match::find($this->match_id);
            $sets = $match->sets;

            if (count($sets) == 0){
                foreach (range(0, 1) as $number) {
                    Set::create([
                        'match_id' => $this->match_id,
                        'score_1' => 11,
                        'score_' => 0
                    ]);
                }
                $match->confirmed = true;
                $match->save();

                $challenger = User::find($match->challenge->challenger->id);
                $asked = User::find($match->challenge->asked->id);
                $pos1 = $asked->position;
                $asked->position = $challenger->position;
                $challenger->position = $pos1;
                $asked->save();
                $challenger->save();
            }
        });
    }
}
