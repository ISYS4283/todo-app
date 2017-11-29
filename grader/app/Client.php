<?php

namespace App;

use InvalidArgumentException;

class Client
{
    protected $ip;
    protected $token;

    public function __construct(string $ip, string $token)
    {
        $this->setIp($ip);
        $this->token = $token;
    }

    protected function setIp(string $ip)
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            throw new InvalidIpAddress("Invalid IPv4: $ip");
        }

        $this->ip = $ip;
    }
}

class InvalidIpAddress extends InvalidArgumentException {}
