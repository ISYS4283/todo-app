<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssignmentTest extends TestCase
{
    public function test_validates_ip()
    {
        $this
            ->post('/', [
                'ip-address' => '300.300.300.300',
            ])
            ->assertSessionHasErrors(['ip-address'])
        ;

        $this
            ->post('/', [
                'ip-address' => '10.10.10.10',
            ])
            ->assertSuccessful()
        ;
    }

    public function test_validates_hostname()
    {
        $this
            ->post('/', [
                'ip-address' => 'example.com',
            ])
            ->assertSessionHasErrors(['ip-address'])
        ;

        $this
            ->post('/', [
                'ip-address' => 'https://example.com',
            ])
            ->assertSuccessful()
        ;
    }
}
