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
        $this->now = Carbon::today('Europe/Bratislava');
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
            $in = $season->date_from <= $this->start && $season->date_to >= $this->end;
            $out = $season->date_from >= $this->start && $season->date_to <= $this->end;
            $before = $season->date_from >= $this->start && $season->date_to >= $this->end;
            $after = $season->date_from <= $this->start && $season->date_to <= $this->end;
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
