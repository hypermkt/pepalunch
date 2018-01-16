<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;

class UserTest extends TestCase
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

    public function testShow()
    {
        $response = $this->call('GET', '/api/users/1?token=' . $this->token);

        $response->assertStatus(200);
        $data = $response->getData(true);

        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('icon_image_url', $data);
        $this->assertArrayHasKey('active', $data);
    }
}
