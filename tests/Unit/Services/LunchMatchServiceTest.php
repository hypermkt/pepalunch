<?php

use App\Services\LunchMatchService;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Lunch;
use App\LunchUser;

class LunchMatchServiceTest extends TestCase
{
    protected $service;

    public function setUp()
    {
        parent::setUp();

        $this->artisan('migrate:fresh');
        $this->service = new LunchMatchService();
    }

    public function testCalculateBaseDate_WhenBefore13()
    {
        $testDate = Carbon::create(2018, 1, 1, 12, 59);
        Carbon::setTestNow($testDate);
        $this->assertEquals(1, $this->service->calculateBaseDate()->day);
        $this->assertEquals(13, $this->service->calculateBaseDate()->hour);
        $this->assertEquals(0, $this->service->calculateBaseDate()->minute);
    }

    public function testCalculateBaseDate_WhenAfter13()
    {
        $testDate = Carbon::create(2018, 1, 1, 13, 0);
        Carbon::setTestNow($testDate);
        $this->assertEquals(2, $this->service->calculateBaseDate()->day);
        $this->assertEquals(13, $this->service->calculateBaseDate()->hour);
        $this->assertEquals(0, $this->service->calculateBaseDate()->minute);
    }

    public function testGetCandidateDates()
    {
        $now = Carbon::now()->setDate(2018, 1, 1)->setTime(13, 0);
        $now1 = clone $now;
        $now2 = clone $now;
        $this->assertEquals([
            clone $now1->setDate(2018, 1, 2),
            clone $now1->setDate(2018, 1, 3),
            clone $now1->setDate(2018, 1, 4),
            clone $now1->setDate(2018, 1, 5),
            clone $now1->setDate(2018, 1, 8),
            clone $now1->setDate(2018, 1, 9),
            clone $now1->setDate(2018, 1, 10),
            clone $now1->setDate(2018, 1, 11),
        ], $this->service->getCandidateDates(1, $now2));
    }

    public function testGetCandidates_ReturnFalse_WhenUserWasAlone()
    {
        factory(App\User::class, 1)->create();
        $testDate = Carbon::create(2018, 1, 1, 12, 59);
        Carbon::setTestNow($testDate);
        $candidateDates = $this->service->getCandidateDates(1, $this->service->calculateBaseDate());

        $this->assertFalse($this->service->getCandidates(1, $candidateDates));
    }

    public function testGetCandidates_WhenUserWasFivePeople()
    {
        factory(App\User::class, 5)->create();
        $testDate = Carbon::create(2018, 1, 1, 12, 59);
        Carbon::setTestNow($testDate);
        $candidateDates = $this->service->getCandidateDates(1, $this->service->calculateBaseDate());

        $this->assertEquals(
            Carbon::now()->setDate(2018, 1, 2)->setTime(13, 0),
            $this->service->getCandidates(1, $candidateDates)['date']
        );
        $this->assertCount(3, $this->service->getCandidates(1, $candidateDates)['candidates']);
    }

    public function testSaveLunch_WillSave_WhenGivenValidParams()
    {
        factory(App\User::class, 5)->create();
        $testDate = Carbon::create(2018, 1, 1, 12, 59);
        Carbon::setTestNow($testDate);
        $candidates = DB::table('users')
            ->where('id', '<>', 1)
            ->take(3)
            ->get();
        $matchedLunch = [
            'date' => Carbon::now()->setDate(2018, 1, 2)->setTime(13, 0),
            'candidates' => $candidates
        ];

        $this->service->saveLunch(1, $matchedLunch);

        $lunch = Lunch::first();
        $this->assertEquals('2018-01-02 13:00:00', $lunch->lunch_at);

        $lunchUsers = LunchUser::where('lunch_id', $lunch->id)->get();
        $this->assertCount(4, $lunchUsers);
    }

    public function testShuffleLunch_WillSave()
    {
        factory(App\User::class, 5)->create();
        $testDate = Carbon::create(2018, 1, 1, 12, 59);
        Carbon::setTestNow($testDate);

        $this->service->shuffleLunch(1);

        $lunch = Lunch::first();
        $this->assertEquals('2018-01-02 13:00:00', $lunch->lunch_at);

        $lunchUsers = LunchUser::where('lunch_id', $lunch->id)->get();
        $this->assertCount(4, $lunchUsers);
    }
}
