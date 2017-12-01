<?php

namespace App;

use InvalidArgumentException;
use Exception;
use Zttp\Zttp;

class Client
{
    protected $host;
    protected $token;

    public function __construct(string $host, string $token)
    {
        $this->sethost($host);
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

    protected function sethost(string $host)
    {
        if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return $this->host = "http://$host";
        }

        return $this->host = $host;
    }

    protected function authenticate()
    {
        return Zttp::withHeaders([
            'X-Token' => $this->token,
        ]);
    }

    protected function url($query = null) : string
    {
        $url = "{$this->host}/todos.php";

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

class BadResponse extends Exception {}
