<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AllSetsValidator implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $asked = 0;
        $challenger = 0;

        foreach ($value as $item) {
            $score1 = (int) $item['score_1'];
            $score2 = (int) $item['score_2'];

            if ( ($score1 >= 10 && $score2 >= 10 && abs($score1 - $score2) == 2) || ($score1 == 11 && $score2 < 10) || ($score2 == 11 && $score1 < 10) ) {
                if ($asked == 2 || $challenger == 2) {
                    return false;
                }
                if ($score1 > $score2) {
                    $challenger++;
                } else {
                    $asked++;
                }
                continue;
            }
            return false;
        }

        if ($asked != 2 && $challenger != 2) {
            return false;
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
        return __('sets.invalid_sets');
    }
}