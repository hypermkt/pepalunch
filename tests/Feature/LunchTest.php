<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LunchTest extends TestCase
{
    public function testLunchIndex()
    {
        $this->json('GET', '/api/lunches')
            ->assertStatus(200);
    }
}
