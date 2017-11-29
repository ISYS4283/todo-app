<?php

namespace App;

use InvalidArgumentException;
use Exception;
use Zttp\Zttp;

class Client
{
    protected $ip;
    protected $token;

    public function __construct(string $ip, string $token)
    {
        $this->setIp($ip);
        $this->token = $token;
    }

    public function get($query = null)
    {
        return $this->send('GET', $query);
    }

    public function post(array $data)
    {
        return $this->send('POST', null, $data);
    }

    public function put(array $data)
    {
        return $this->send('PUT', $data['id'], $data);
    }

    public function delete(int $id)
    {
        return $this->send('DELETE', $id);
    }

    protected function setIp(string $ip)
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            throw new InvalidIpAddress("Invalid IPv4: $ip");
        }

        $this->ip = $ip;
    }

    protected function authenticate()
    {
        return Zttp::withHeaders([
            'X-Token' => $this->token,
        ]);
    }

    protected function url($query = null) : string
    {
        $url = "http://{$this->ip}/todos.php";

        if (filter_var($query, FILTER_VALIDATE_INT)) {
            return "$url?id=$query";
        }

        if ($query === 'all') {
            return "$url?all";
        }

        return $url;
    }

    protected function send(string $method, $query = null, array $data = [])
    {
        $response = $this->authenticate()->$method($this->url($query), $data);

        if (!$response->isOk()) {
            $code = $response->status();
            throw new BadResponse("Error:$code\n{$response->body()}", $code);
        }

        return $response->json();
    }
}

class InvalidIpAddress extends InvalidArgumentException {}
class BadResponse extends Exception {}
