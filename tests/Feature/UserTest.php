<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;

class LunchTest extends TestCase
{
    protected $token;

    public function setUp()
    {
        parent::setUp();

        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');

        $user = User::find(1);
        $this->token = JWTAuth::fromUser($user);
    }

    public function testUpdate()
    {
        $response = $this->call('PUT', '/api/users/1?token=' . $this->token);
        $this->assertEquals(204, $response->getStatusCode());
    }
}
