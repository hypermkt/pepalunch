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

    public function testLunchIndex()
    {
        $response = $this->call('GET', '/api/lunches');

        $this->assertEquals(200, $response->getStatusCode());
        $data = $response->getData(true);
        $this->assertArrayHasKey('id', $data[0]);
        $this->assertArrayHasKey('lunch_at', $data[0]);
        $this->assertArrayHasKey('id', $data[0]['users'][0]);
        $this->assertArrayHasKey('name', $data[0]['users'][0]);
    }
}
