<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssignmentTest extends TestCase
{
    protected function makeAssignment(array $override = [])
    {
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
}
