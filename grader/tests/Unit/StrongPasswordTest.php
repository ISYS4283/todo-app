<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Rules\StrongPassword;
use ISYS4283\ToDo\Authenticator;

class StrongPasswordTest extends TestCase
{
    /**
     * @return bool|string If passes, then true, else error message.
     */
    protected function check(string $password = null)
    {
        $token = $this->fakeToken(['password' => $password]);

        $rule = new StrongPassword;

        if ($rule->passes('password', $token)) {
            return true;
        }

        return $rule->message();
    }

    public function fakeToken(array $override = []) : string
    {
        if (isset($override['password'])) {
            return Authenticator::fake($override)->getToken();
        }

        return Authenticator::fake()->getToken();
    }

    public function test_validates_factory()
    {
        $pass = $this->check();
        $this->assertTrue($pass);
    }

    public function getFailureTestCases()
    {
        return [
            'too short' => [
                'password' => 'Too Short 12',
                'error' => 'Your password is too short! Must be at least 13 characters.',
            ],
            'no uppercase' => [
                'password' => 'this 1 password has no uppercase letters',
                'error' => 'Your password needs at least one uppercase letter.',
            ],
        ];
    }

    /**
     * @dataProvider getFailureTestCases
     */
    public function test_validates_password(string $password, string $error)
    {
        $this->assertSame($error, $this->check($password));
    }
}
