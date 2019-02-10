<?php

namespace App\Rules;

use App\Season;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ValidSeason implements Rule
{
    public $start;
    public $end;
    public $now;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($start, $end)
    {
        $this->start = Carbon::parse($start);
        $this->end = Carbon::parse($end);
        $this->now = Carbon::today(config('app.timezone'));
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->start < $this->now || $this->end < $this->start) {
            return false;
        }
        $allSeasons = Season::available();

        foreach ($allSeasons as $season) {
            $seasonStart = Carbon::parse($season->date_from);
            $seasonEnd = Carbon::parse($season->date_to);
            $in = $seasonStart <= $this->start && $this->end <= $seasonEnd;
            $out =  $this->start <= $seasonStart && $seasonEnd <= $this->end;
            $before = $this->start <= $seasonStart && $seasonStart <= $this->end && $this->end <= $seasonEnd;
            $after = $seasonStart <= $this->start && $this->start <= $seasonEnd && $seasonEnd <= $this->end;
            if ($in || $out || $before || $after) {
                return false;
            }
        }
        return true;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('season.can_not_add_rule');
    }
}
