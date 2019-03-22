<?php

namespace App\Rules;

use App\Challenge;
use App\Date;
use Illuminate\Contracts\Validation\Rule;

class UniqueDate implements Rule
{
    public $challenge = null;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($chalenge_id)
    {
        $this->challenge = Challenge::find($chalenge_id);
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
        return !Date::where('date', $value)->where('deleted_at', null)->where('challenge_id', $this->challenge->id)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.unique');
    }
}
