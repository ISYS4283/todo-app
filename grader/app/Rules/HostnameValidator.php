<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Validator;

class HostnameValidator implements Rule
{
    protected $validator;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->validator = Validator::make([],[]);
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
        return (
            $this->validator->validateIpv4($attribute, $value)
            or
            $this->validator->validateUrl($attribute, $value)
        );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The hostname must be a valid IPv4 address or hostname URL.';
    }
}
