<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Client;
use App\BadResponse;
use Exception;
use GuzzleHttp\Exception\RequestException;

class UserPermissions implements Rule
{
    protected $message;

    protected function fail($e) : bool
    {
        if ($e instanceof Exception) {
            $e = get_class($e).': '.$e->getMessage();
        }

        $this->message = $e;

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
        $client = new Client(request()->get('ip-address'), $value);

        try {
            $response = $client->get();
        } catch (BadResponse $e) {
            return $this->fail($e);
        } catch (RequestException $e) {
            return $this->fail($e);
        }

        if (!is_array($response)) {
            return $this->fail('Response did not return array.');
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
