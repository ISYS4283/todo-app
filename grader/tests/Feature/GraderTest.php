<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Zttp\Zttp;

class GraderTest extends TestCase
{
    public function user()
    {
        return Zttp::withHeaders([
            'X-Token' => getenv('REGULAR_USER_TOKEN'),
        ]);
    }

    public function userGet(array $data = [])
    {
        return $this->user()->get($this->url(), $data)->json();
    }

    public function url()
    {
        return 'http://localhost:' . getenv('TEST_SERVER_PORT');
    }

    public function test_can_connect_to_server()
    {
        $todos = $this->userGet();

        $this->assertSame([], $todos);
    }
}
