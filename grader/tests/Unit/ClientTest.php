<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Client;
use App\ToDo;

class ClientTest extends TestCase
{
    public function test_can_instantiate_object()
    {
        $ip = "10.9.12.200";
        $token = "token";

        $this->assertInstanceOf(Client::class, new Client($ip, $token));
    }

    protected function getClient(string $token) : Client
    {
        $host = 'http://localhost:' . getenv('TEST_SERVER_PORT');
        return new Client($host, $token);
    }

    protected function getUser() : Client
    {
        return $this->getClient(getenv('REGULAR_USER_TOKEN'));
    }

    public function test_user_can_connect_to_server()
    {
        $user = $this->getUser();
        $this->assertTrue(is_array($user->get()));
    }

    public function test_user_can_create_todo()
    {
        $user = $this->getUser();

        $expected = factory(ToDo::class)->make()->toArray();

        $actual = $user->post($expected);

        $this->assertArraySubset($expected, $actual);
        $this->assertArrayHasKey('id', $actual);

        return $actual;
    }

    /**
     * @depends test_user_can_create_todo
     */
    public function test_user_can_update_todo(array $todo)
    {
        $expected = factory(ToDo::class)->make()->toArray();
        $expected['id'] = $todo['id'];

        $actual = $this->getUser()->put($expected);

        $this->assertEquals($expected, $actual);

        return $actual;
    }

    /**
     * @depends test_user_can_update_todo
     */
    public function test_user_can_delete_todo(array $todo)
    {
        $this->assertTrue($this->getUser()->delete($todo['id']));
    }
}
