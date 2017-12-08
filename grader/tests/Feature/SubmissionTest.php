<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ISYS4283\ToDo\Authenticator;

class SubmissionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Factory for live testing a network connection.
     * See also factory for fake Submission::class
     */
    protected function makeLiveSubmission(array $override = []) : array
    {
        if (is_array($override['user_token'] ?? null)) {
            $override['user_token'] = Authenticator::fake($override['user_token'])->getToken();
        }

        return array_replace([
            'host' => 'http://localhost:' . getenv('TEST_SERVER_PORT'),
            'user_token' => getenv('REGULAR_USER_TOKEN')
        ], $override);
    }

    public function test_creates_submission()
    {
        $expected = $this->makeLiveSubmission();
        $user = factory(\App\User::class)->create();

        $this
            ->signIn($user)
            ->post('/', $expected)
            ->assertSuccessful()
        ;

        $expected['user_id'] = $user->id;
        $this->assertDatabaseHas('submissions', $expected);
    }

    public function test_validates_token_instance()
    {
        $this
            ->signIn()
            ->post('/', $this->makeLiveSubmission([
                'user_token' => 'Not Base64',
            ]))
            ->assertSessionHasErrors(['user_token'])
        ;
    }
}
