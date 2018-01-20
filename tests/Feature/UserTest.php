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
        $this->call('PUT', '/api/users/1?token=' . $this->token)
            ->assertStatus(204);
    }

    public function testShow()
    {
        $this->call('GET', '/api/users/1?token=' . $this->token)
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'icon_image_url',
                'active',
            ]);
    }
}
