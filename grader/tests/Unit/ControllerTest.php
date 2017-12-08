<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ISYS4283\ToDo\Controller;

class ControllerTest extends TestCase
{
    public function test_can_instantiate_object()
    {
        $this->assertInstanceOf(Controller::class, new Controller);
    }
}
