<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Zttp\Zttp;
use App\ToDo;

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

    public function userPost(array $data)
    {
        return $this->user()->post($this->url(), $data)->json();
    }

    public function url()
    {
        return 'http://localhost:' . getenv('TEST_SERVER_PORT');
    }

    public function test_can_connect_to_server()
    {
        $this->assertTrue(is_array($this->userGet()));
    }

    public function test_user_can_create_todo()
    {
        $expected = factory(ToDo::class)->make()->toArray();

        $actual = $this->userPost($expected);

        $this->assertArraySubset($expected, $actual);
        $this->assertArrayHasKey('id', $actual);
    }
}
