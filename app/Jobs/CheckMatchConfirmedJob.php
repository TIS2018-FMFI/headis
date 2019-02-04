<?php

namespace App\Jobs;

use App\Match;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class CheckMatchConfirmedJob implements ShouldQueue
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
            if ($match->confirmed == NULL){
                $match->confirmed = true;
                $match->save();
            }
        });
    }
}
