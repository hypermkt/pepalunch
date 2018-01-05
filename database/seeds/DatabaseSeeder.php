<?php

use Illuminate\Database\Seeder;
use App\User;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 5)->create();
        $candidates = \LunchMatch::getCandidates(User::first()->id, [Carbon::now()]);
        \LunchMatch::saveLunch(User::first()->id, $candidates);
    }
}
