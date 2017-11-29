<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Client;

class ClientTest extends TestCase
{
    public function test_can_validate_ip()
    {
        $ip = "10.9.12.200";
        $token = "token";

        $this->assertInstanceOf(Client::class, new Client($ip, $token));
    }

    /**
     * @expectedException \App\InvalidIpAddress
     */
    public function test_can_invalidate_ip()
    {
        $ip = "10.9.12.200.4";
        $token = "token";
        new Client($ip, $token);
    }
}
