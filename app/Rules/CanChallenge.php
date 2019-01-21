<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class CanChallenge implements Rule
{
    public $challenger;

    public $asked;
    /**
     * Create a new rule instance.
     *
     * @param integer $challenger_id
     * @param integer $asked_id
     * @return void
     */
    public function __construct($challenger_id, $asked_id)
    {
        $this->challenger = User::find($challenger_id);
        $this->asked = User::find($asked_id);
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
        return ($this->challenger->position > $this->asked->position) &&
            (floor(sqrt($this->challenger->position - 1)) === floor(sqrt($this->asked->position - 1)) ||
             floor(sqrt($this->challenger->position - 1)) - 1 === floor(sqrt($this->asked->position - 1))) &&
            ($this->challenger->currentMatch() == null && $this->asked->currentMatch() == null) &&
            (User::currentChallenge($this->challenger) == null && User::currentChallenge($this->asked) == null) &&
            ($this->asked->countOfChallengesAsAsked() < 3 && $this->challenger->countOfChallengesAsChallenger() < 3) &&
            !$this->asked->isRedactor && !$this->challenger->isRedactor;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Can Challenge Error');
    }
}
