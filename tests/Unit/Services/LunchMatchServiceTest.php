<?php

use App\Services\LunchMatchService;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LunchMatchServiceTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->artisan('migrate:fresh');
    }

    public function testCalculateBaseDate_WhenBefore13()
    {
        $testDate = Carbon::create(2018, 1, 1, 12, 59);
        Carbon::setTestNow($testDate);
        $service = new LunchMatchService();
        $this->assertEquals(1, $service->calculateBaseDate()->day);
        $this->assertEquals(13, $service->calculateBaseDate()->hour);
        $this->assertEquals(0, $service->calculateBaseDate()->minute);
    }

    public function testCalculateBaseDate_WhenAfter13()
    {
        $testDate = Carbon::create(2018, 1, 1, 13, 0);
        Carbon::setTestNow($testDate);
        $service = new LunchMatchService();
        $this->assertEquals(2, $service->calculateBaseDate()->day);
        $this->assertEquals(13, $service->calculateBaseDate()->hour);
        $this->assertEquals(0, $service->calculateBaseDate()->minute);
    }

    public function testGetCandidateDates()
    {
        $now = Carbon::now()->setDate(2018, 1, 1)->setTime(13, 0);
        $now1 = clone $now;
        $now2 = clone $now;
        $service = new LunchMatchService();
        $this->assertEquals([
            clone $now1->setDate(2018, 1, 2),
            clone $now1->setDate(2018, 1, 3),
            clone $now1->setDate(2018, 1, 4),
            clone $now1->setDate(2018, 1, 5),
            clone $now1->setDate(2018, 1, 8),
            clone $now1->setDate(2018, 1, 9),
            clone $now1->setDate(2018, 1, 10),
            clone $now1->setDate(2018, 1, 11),
        ], $service->getCandidateDates(1, $now2));
    }

    public function testGetCandidates_WhenUserWasFivePeople()
    {
        factory(App\User::class, 5)->create();
        $testDate = Carbon::create(2018, 1, 1, 12, 59);
        Carbon::setTestNow($testDate);
        $service = new LunchMatchService();
        $candidateDates = $service->getCandidateDates(1, $service->calculateBaseDate());

        $this->assertEquals(
            Carbon::now()->setDate(2018, 1, 2)->setTime(13, 0),
            $service->getCandidates(1, $candidateDates)['date']
        );
        $this->assertCount(3, $service->getCandidates(1, $candidateDates)['candidates']);
    }
}
