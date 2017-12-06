<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ISYS4283\ToDo\Authenticator;
use App\Client;
use App\ToDo;

class AssignmentTest extends TestCase
{
    protected function makeAssignment(array $override = [])
    {
        if (is_array($override['user-token'] ?? null)) {
            $override['user-token'] = Authenticator::fake($override['user-token'])->getToken();
        }

        return array_replace([
            'ip-address' => 'http://localhost:' . getenv('TEST_SERVER_PORT'),
            'user-token' => getenv('REGULAR_USER_TOKEN')
        ], $override);
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

    public function test_creates_assignment()
    {
        $this
            ->post('/', $this->makeAssignment())
            ->assertSuccessful()
        ;
    }

    public function test_validates_token_instance()
    {
        $this
            ->post('/', $this->makeAssignment([
                'user-token' => 'Not Base64',
            ]))
            ->assertSessionHasErrors(['user-token'])
        ;
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
