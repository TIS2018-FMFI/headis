<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SetValidator implements Rule
{
    public $score1;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($score1)
    {
        $this->score1 = $score1;
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

        $score1 = (int) $this->score1;
        $score2 = (int) $value;

        if ( $score1 >= 10 && $score2 >= 10 && abs($score1 - $score2) > 1){
            return true;
        }
        else if($score1 == 11 || $score2 == 11){
            if (abs($score1 - $score2) > 1){
                return true;
            }
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
