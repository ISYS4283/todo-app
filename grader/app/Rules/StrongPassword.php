<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use ISYS4283\ToDo\Authenticator;

class StrongPassword implements Rule
{
    protected $message = 'The validation error message.';

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
        $password = Authenticator::createFromToken($value)->getCredentials()['password'];

        if (strlen($password) < 13) {
            $this->message = 'Your password is too short! Must be at least 13 characters.';
            return false;
        }

        if (strtolower($password) === $password) {
            $this->message = 'Your password needs at least one uppercase letter.';
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
        return $this->message;
    }
}
