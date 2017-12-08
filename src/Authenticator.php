<?php

namespace ISYS4283\ToDo;

use InvalidArgumentException;

class Authenticator
{
    protected $credentials;

    public function __construct(string $username, string $password, string $database, string $hostname = 'localhost')
    {
        $this->credentials['username'] = $username;
        $this->credentials['password'] = $password;
        $this->credentials['database'] = $database;
        $this->credentials['hostname'] = $hostname;
    }

    public function getCredentials() : array
    {
        return $this->credentials;
    }

    public function getToken() : string
    {
        return base64_encode(json_encode($this->getCredentials()));
    }

    public static function createFromArray(array $credentials) : Authenticator
    {
        $required = [
            'username',
            'password',
            'database',
        ];

        foreach ($required as $credential) {
            if (empty($credentials[$credential])) {
                throw new MissingParameter("Missing required parameter: $credential");
            }
            $validated[$credential] = $credentials[$credential];
        }

        if (!empty($credentials['hostname'])) {
            $validated['hostname'] = $credentials['hostname'];
        }

        return new static(...array_values($validated));
    }

    public static function createFromTokenHeader() : Authenticator
    {
        $token = $_SERVER['HTTP_X_TOKEN'] ?? null;
        if (empty($token)) {
            throw new MissingToken;
        }

        return static::createFromToken($token);
    }

    public static function createFromToken(string $token) : Authenticator
    {
        $json = base64_decode($token);
        if ($json === false) {
            throw new BadBase64Encoding;
        }

        $credentials = json_decode($json, true);
        if (empty($credentials)) {
            throw new BadJsonEncoding;
        }

        return static::createFromArray($credentials);
    }

    public static function createFromSession()
    {
        static::startSession();

        $credentials = $_SESSION['credentials'];

        if (empty($credentials)) {
            throw new MissingSessionCredentials;
        }

        return static::createFromArray($credentials);
    }

    public function saveToSession()
    {
        static::startSession();

        $_SESSION['credentials'] = $this->getCredentials();
    }

    public static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function createFromFormRequest()
    {
        return static::createFromArray($_POST);
    }

    public static function fake(array $override = []) : Authenticator
    {
        $faker = \Faker\Factory::create();

        return static::createFromArray(array_replace([
            'hostname' => 'localhost',
            'database' => $faker->word,
            'username' => $faker->word,
            'password' => 'ISYS4283 is the best!',
        ], $override));
    }
}

class MissingParameter extends InvalidArgumentException {}
class MissingSessionCredentials extends InvalidArgumentException {}
class MissingToken extends InvalidArgumentException {}
class BadBase64Encoding extends InvalidArgumentException {}
class BadJsonEncoding extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct(json_last_error_msg(), json_last_error());
    }
}
