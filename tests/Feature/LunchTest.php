<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LunchTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');
    }

    public function testIndex()
    {
        $response = $this->call('GET', '/api/lunches');

        $this->assertEquals(200, $response->getStatusCode());
        $data = $response->getData(true);
        $this->assertArrayHasKey('id', $data[0]);
        $this->assertArrayHasKey('lunch_at', $data[0]);
        $this->assertArrayHasKey('id', $data[0]['users'][0]);
        $this->assertArrayHasKey('name', $data[0]['users'][0]);
    }

    public function testStore()
    {
        $response = $this->call('POST', '/api/lunches');
        $this->assertEquals(201, $response->getStatusCode());
        $data = $response->getData(true);
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('lunch_at', $data);
        $this->assertArrayHasKey('lunch_id', $data['lunch_users'][0]);
        $this->assertArrayHasKey('user_id', $data['lunch_users'][0]);
    }
}
