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
    protected $client;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->client = new Client(request()->get('ip-address'), $value);

        $assertions = [
            'read',
        ];

        foreach ($assertions as $assertion) {
            try {
                $method = "assert$assertion";
                $this->$method();
            } catch (BadResponse $e) {
                return $this->fail($e);
            } catch (RequestException $e) {
                return $this->fail($e);
            } catch (Failure $e) {
                return $this->fail($e);
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
        return $this->message;
    }

    protected function fail($e) : bool
    {
        if ($e instanceof Exception) {
            $e = get_class($e).': '.$e->getMessage();
        }

        $this->message = $e;

        return false;
    }

    protected function assertRead()
    {
        $response = $this->client->get();

        if (!is_array($response)) {
            throw new Failure('Cannot read data.');
        }
    }
}

class Failure extends Exception {}
