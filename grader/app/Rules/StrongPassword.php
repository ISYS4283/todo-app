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

    protected function fail(string $error)
    {
        $this->message = $error;
        return false;
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
            return $this->fail('Your password is too short! Must be at least 13 characters.');
        }

        if (strtolower($password) === $password) {
            return $this->fail('Your password needs at least one uppercase letter.');
        }

        if (strtoupper($password) === $password) {
            return $this->fail('Your password needs at least one lowercase letter.');
        }

        if (strcspn($password, '0123456789') === strlen($password)) {
            return $this->fail('Your password needs at least one number.');
        }

        if (ctype_alnum($password)) {
            return $this->fail('Your password needs at least one special character.');
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
