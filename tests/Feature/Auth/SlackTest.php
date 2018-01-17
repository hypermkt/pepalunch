<?php

namespace Tests\Feature\Auth;

use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SlackTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->artisan('migrate:fresh');
    }

    public function testAuthSlackCallback()
    {
        $slackUser = new \stdClass();
        $slackUser->accessTokenResponseBody = [
            'access_token' => 'xxx',
            'user' => [
                'id' => 1,
                'name' => 'hoge',
                'image_72' => 'fuga',
            ]
        ];
        Socialite::shouldReceive('driver->stateless->user')->andReturn($slackUser);

        $this->call('POST', '/api/login')
            ->assertStatus(200);
    }
}
