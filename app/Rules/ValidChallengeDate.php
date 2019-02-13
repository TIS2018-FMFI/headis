<?php

namespace App\Rules;

use App\Challenge;
use App\NotAvailableDate;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ValidChallengeDate implements Rule
{
    public $challenge;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($challange_id)
    {
        $this->challenge = Challenge::find($challange_id);
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
        $date = Carbon::parse($value);
        $now = Carbon::now(config('app.timezone'));
        $created_date = Carbon::parse($this->challenge->create_date);

        $end = NotAvailableDate::addDaysTo($created_date, 10);

        return $created_date < $date && $now < $date && $date <= $end && !NotAvailableDate::where('date', $date->toDateString())->exists();

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('challenges.invalid_date');
    }
}
