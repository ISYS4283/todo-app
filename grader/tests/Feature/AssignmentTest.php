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
            'ip-address' => '10.10.10.10',
            'user-token' => 'eyJ1c2VybmFtZSI6ImZhY2VyZSIsInBhc3N3b3JkIjoiSVNZUzQyODMgaXMgdGhlIGJlc3QhIiwiZGF0YWJhc2UiOiJzb2x1dGEiLCJob3N0bmFtZSI6ImxvY2FsaG9zdCJ9',
        ], $override);
    }

    public function test_creates_assignment()
    {
        $this
            ->post('/', $this->makeAssignment())
            ->assertSuccessful()
        ;
    }

    public function test_validates_ip()
    {
        $this
            ->post('/', $this->makeAssignment([
                'ip-address' => '300.300.300.300',
            ]))
            ->assertSessionHasErrors(['ip-address'])
        ;
    }

    public function test_validates_hostname()
    {
        $this
            ->post('/', $this->makeAssignment([
                'ip-address' => 'example.com',
            ]))
            ->assertSessionHasErrors(['ip-address'])
        ;

        $this
            ->post('/', $this->makeAssignment([
                'ip-address' => 'https://example.com',
            ]))
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

    public function test_validates_uppercase_letters()
    {
        $this
            ->post('/', $this->makeAssignment([
                'user-token' => [
                    'password' => 'this is an undercase password 1',
                ],
            ]))
            ->assertSessionHasErrors(['user-token'])
        ;
    }

    public function test_validates_good_password()
    {
        $this
            ->post('/', $this->makeAssignment([
                'user-token' => [
                    'password' => 'This is a good password 1',
                ],
            ]))
            ->assertSuccessful()
        ;
    }
}
