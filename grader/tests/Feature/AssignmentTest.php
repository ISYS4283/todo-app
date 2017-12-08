<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ISYS4283\ToDo\Authenticator;

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

    public function test_creates_assignment()
    {
        $this
            ->signIn()
            ->post('/', $this->makeAssignment())
            ->assertSuccessful()
        ;
    }

    public function test_validates_token_instance()
    {
        $this
            ->signIn()
            ->post('/', $this->makeAssignment([
                'user-token' => 'Not Base64',
            ]))
            ->assertSessionHasErrors(['user-token'])
        ;
    }
}
