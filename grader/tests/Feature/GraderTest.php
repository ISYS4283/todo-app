<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Zttp\Zttp;
use App\ToDo;

class GraderTest extends TestCase
{
    public function url() : string
    {
        return 'http://localhost:' . getenv('TEST_SERVER_PORT');
    }

    public function user()
    {
        return Zttp::withHeaders([
            'X-Token' => getenv('REGULAR_USER_TOKEN'),
        ]);
    }

    public function userGet(array $data = []) : array
    {
        return $this->user()->get($this->url(), $data)->json();
    }

    public function userPut(array $todo) : array
    {
        return $this->user()->put("{$this->url()}?id=$todo[id]", $todo)->json();
    }

    public function userPost(array $data) : array
    {
        return $this->user()->post($this->url(), $data)->json();
    }

    public function userDelete(array $todo) : bool
    {
        return $this->user()->delete("{$this->url()}?id=$todo[id]")->status() == 204;
    }

    public function test_user_can_create_todo()
    {
        $expected = factory(ToDo::class)->make()->toArray();

        $actual = $this->userPost($expected);

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

        $actual = $this->userPut($expected);

        $this->assertEquals($expected, $actual);

        return $actual;
    }

    /**
     * @depends test_user_can_update_todo
     */
    public function test_user_can_delete_todo(array $todo)
    {
        $this->assertTrue($this->userDelete($todo));
    }
}
