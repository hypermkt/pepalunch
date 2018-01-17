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

    public function testIndex()
    {
        $this->call('GET', '/api/lunches?token=' . $this->token)
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'lunch_at',
                    'created_at',
                    'updated_at',
                    'users' => [
                        '*' => [
                            'id',
                            'name',
                        ]
                    ]
                ]
            ]);
    }

    public function testStore()
    {
        $this->call('POST', '/api/lunches?token=' . $this->token)
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'lunch_at',
                'created_at',
                'updated_at',
                'lunch_users' => [
                    '*' => [
                        'id',
                        'lunch_id',
                        'user_id',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }
}
