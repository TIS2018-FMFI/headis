<?php

namespace App\Jobs;

use App\Challenge;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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
                //tu bude penalizacia
            }

        });
    }
}
