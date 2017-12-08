<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Client;
use App\BadResponse;
use Exception;
use GuzzleHttp\Exception\RequestException;
use App\ToDo;

class UserPermissions implements Rule
{
    protected $message;
    protected $client;
    protected $id;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->client = new Client(request()->get('host'), $value);

        $assertions = [
            'create',
            'read',
            'update',
            'delete',
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

    protected function assertCreate()
    {
        $expected = factory(ToDo::class)->make()->toArray();

        $actual = $this->client->post($expected);

        if (!is_array($actual)) {
            throw new Failure('Cannot create data. Response is not an array.');
        }

        if (!empty(array_diff_assoc($expected, $actual))) {
            throw new Failure('Cannot create data. Actual does not match expected.');
        }

        if (!array_key_exists('id', $actual)) {
            throw new Failure('Cannot create data. Response does not contain ID.');
        }

        $this->id = $actual['id'];
    }

    protected function assertRead()
    {
        $response = $this->client->get();

        if (!is_array($response)) {
            throw new Failure('Cannot read data.');
        }
    }

    protected function assertUpdate()
    {
        $expected = factory(ToDo::class)->make()->toArray();
        $expected['id'] = $this->id;

        $actual = $this->client->put($expected);

        if (!is_array($actual)) {
            throw new Failure('Cannot update data. Response is not an array.');
        }

        if (!empty(array_diff_assoc($expected, $actual))) {
            throw new Failure('Cannot update data. Actual does not match expected.');
        }
    }

    protected function assertDelete()
    {
        if ($this->client->delete($this->id) !== true) {
            throw new Failure('Cannot delete data.');
        }
    }
}

class Failure extends Exception {}
